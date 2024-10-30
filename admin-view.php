<?php
const IMAGES_COUNT = 500;

$path    = dirname( dirname( dirname( __FILE__ ) ) ) . "/uploads";
$objects = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $path ), RecursiveIteratorIterator::SELF_FIRST );

function compress_jpeg_hardly( $input )
{
	// load image, check and quit if not jpeg
	$img = @ imagecreatefromjpeg( $input );
	if ( $img === FALSE )
	{
		return FALSE;
	}

	// get source file size
	$src_size = filesize( $input );

	// backup file
	// copy( $input, $input . "-jpeg-compressor-backup" );

	imagejpeg( $img, $input, 80 );

	// get destination file size
	clearstatcache();
	$dst_size = filesize( $input );

	// calculate and return compression percent
	$percent = round( 100 - ( ( $dst_size / $src_size ) * 100 ), 2 );
	if ( $percent < 0 )
	{
		return "<span style='color:red;'><b>+" . abs( $percent ) . "%</b></span>";
	}
	else
	{
		return "<span style='color:green;'><b>-" . $percent . "%</b></span>";
	}
}

$skip      = 0;
$processed = [];
if ( isset( $_REQUEST["action"] ) && ( $_REQUEST["action"] == "compress" ) )
{
	$curr = -1;
	$skip = intval( $_REQUEST["skip"] );

	foreach ( $objects as $name => $object )
	{
		// skip to current pointer
		$curr++;
		if ( $curr <= $skip )
		{
			continue;
		}

		// try to compress file
		$ret = compress_jpeg_hardly( $name );
		if ( $ret !== FALSE )
		{
			$processed[] = $name . " " . $ret;
		}

		// limit compressed files at time
		if ( $curr >= $skip + IMAGES_COUNT )
		{
			break;
		}
	}

	$skip += IMAGES_COUNT;
}

// build query for next files
$data  = [ "action" => "compress", "skip" => $skip ];
$query = http_build_query( $data );

?>
<div class="wrap">
        <h1>JPEG Compressor</h1>
        <p><?php echo iterator_count( $objects ); ?> files & folders found</p>

	<?php if ( $skip < iterator_count( $objects ) ): ?>
                <a href="/wp-admin/admin.php?page=jpeg-images-compressor%2Fadmin-view.php&<?php echo $query; ?>" class="button-primary">
                        Compress <?php echo IMAGES_COUNT; ?> images
			<?php echo " ($skip - " . intval( $skip + IMAGES_COUNT ) . ")"; ?>
                </a>
                <br/><br/>
	<?php endif; ?>

	<?php
	if ( !empty( $processed ) )
	{
		echo "Processed files:<br/>";
	}

	foreach ( $processed as $name )
	{
		echo "$name<br/>";
	}
	?>
</div>
