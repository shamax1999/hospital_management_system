<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CI_Image_lib {

	var $image_library		= 'gd2';	
	var $library_path		= '';
	var $dynamic_output		= FALSE;
	var $source_image		= '';
	var $new_image			= '';
	var $width				= '';
	var $height				= '';
	var $quality			= '90';
	var $create_thumb		= FALSE;
	var $thumb_marker		= '_thumb';
	var $maintain_ratio		= TRUE;		
	var $master_dim			= 'auto';	
	var $rotation_angle		= '';
	var $x_axis				= '';
	var	$y_axis				= '';

	
	var $wm_text			= '';			
	var $wm_type			= 'text';		
	var $wm_x_transp		= 4;
	var $wm_y_transp		= 4;
	var $wm_overlay_path	= '';			
	var $wm_font_path		= '';			
	var $wm_font_size		= 17;			
	var $wm_vrt_alignment	= 'B';			
	var $wm_hor_alignment	= 'C';			
	var $wm_padding			= 0;			
	var $wm_hor_offset		= 0;			
	var $wm_vrt_offset		= 0;			
	var $wm_font_color		= '#ffffff';	// Text color
	var $wm_shadow_color	= '';			
	var $wm_shadow_distance	= 2;			
	var $wm_opacity			= 50;			

	
	var $source_folder		= '';
	var $dest_folder		= '';
	var $mime_type			= '';
	var $orig_width			= '';
	var $orig_height		= '';
	var $image_type			= '';
	var $size_str			= '';
	var $full_src_path		= '';
	var $full_dst_path		= '';
	var $create_fnc			= 'imagecreatetruecolor';
	var $copy_fnc			= 'imagecopyresampled';
	var $error_msg			= array();
	var $wm_use_drop_shadow	= FALSE;
	var $wm_use_truetype	= FALSE;

	
	public function __construct($props = array())
	{
		if (count($props) > 0)
		{
			$this->initialize($props);
		}

		log_message('debug', "Image Lib Class Initialized");
	}

	
	function clear()
	{
		$props = array('source_folder', 'dest_folder', 'source_image', 'full_src_path', 'full_dst_path', 'new_image', 'image_type', 'size_str', 'quality', 'orig_width', 'orig_height', 'width', 'height', 'rotation_angle', 'x_axis', 'y_axis', 'create_fnc', 'copy_fnc', 'wm_overlay_path', 'wm_use_truetype', 'dynamic_output', 'wm_font_size', 'wm_text', 'wm_vrt_alignment', 'wm_hor_alignment', 'wm_padding', 'wm_hor_offset', 'wm_vrt_offset', 'wm_font_color', 'wm_use_drop_shadow', 'wm_shadow_color', 'wm_shadow_distance', 'wm_opacity');

		foreach ($props as $val)
		{
			$this->$val = '';
		}

		
		$this->master_dim = 'auto';
	}

	
	function initialize($props = array())
	{
		
		if (count($props) > 0)
		{
			foreach ($props as $key => $val)
			{
				$this->$key = $val;
			}
		}

		
		if ($this->source_image == '')
		{
			$this->set_error('imglib_source_image_required');
			return FALSE;	
		}

		
		if ( ! function_exists('getimagesize'))
		{
			$this->set_error('imglib_gd_required_for_props');
			return FALSE;
		}

		$this->image_library = strtolower($this->image_library);

		
		if (function_exists('realpath') AND @realpath($this->source_image) !== FALSE)
		{
			$full_source_path = str_replace("\\", "/", realpath($this->source_image));
		}
		else
		{
			$full_source_path = $this->source_image;
		}

		$x = explode('/', $full_source_path);
		$this->source_image = end($x);
		$this->source_folder = str_replace($this->source_image, '', $full_source_path);

		
		if ( ! $this->get_image_properties($this->source_folder.$this->source_image))
		{
			return FALSE;	
		}

		
		if ($this->new_image == '')
		{
			$this->dest_image = $this->source_image;
			$this->dest_folder = $this->source_folder;
		}
		else
		{
			if (strpos($this->new_image, '/') === FALSE AND strpos($this->new_image, '\\') === FALSE)
			{
				$this->dest_folder = $this->source_folder;
				$this->dest_image = $this->new_image;
			}
			else
			{
				if (function_exists('realpath') AND @realpath($this->new_image) !== FALSE)
				{
					$full_dest_path = str_replace("\\", "/", realpath($this->new_image));
				}
				else
				{
					$full_dest_path = $this->new_image;
				}

				
				if ( ! preg_match("#\.(jpg|jpeg|gif|png)$#i", $full_dest_path))
				{
					$this->dest_folder = $full_dest_path.'/';
					$this->dest_image = $this->source_image;
				}
				else
				{
					$x = explode('/', $full_dest_path);
					$this->dest_image = end($x);
					$this->dest_folder = str_replace($this->dest_image, '', $full_dest_path);
				}
			}
		}

		
		if ($this->create_thumb === FALSE OR $this->thumb_marker == '')
		{
			$this->thumb_marker = '';
		}

		$xp	= $this->explode_name($this->dest_image);

		$filename = $xp['name'];
		$file_ext = $xp['ext'];

		$this->full_src_path = $this->source_folder.$this->source_image;
		$this->full_dst_path = $this->dest_folder.$filename.$this->thumb_marker.$file_ext;


		if ($this->maintain_ratio === TRUE && ($this->width != '' AND $this->height != ''))
		{
			$this->image_reproportion();
		}

		
		if ($this->width == '')
			$this->width = $this->orig_width;

		if ($this->height == '')
			$this->height = $this->orig_height;

		
		$this->quality = trim(str_replace("%", "", $this->quality));

		if ($this->quality == '' OR $this->quality == 0 OR ! is_numeric($this->quality))
			$this->quality = 90;

		
		$this->x_axis = ($this->x_axis == '' OR ! is_numeric($this->x_axis)) ? 0 : $this->x_axis;
		$this->y_axis = ($this->y_axis == '' OR ! is_numeric($this->y_axis)) ? 0 : $this->y_axis;

		
		if ($this->wm_font_color != '')
		{
			if (strlen($this->wm_font_color) == 6)
			{
				$this->wm_font_color = '#'.$this->wm_font_color;
			}
		}

		if ($this->wm_shadow_color != '')
		{
			if (strlen($this->wm_shadow_color) == 6)
			{
				$this->wm_shadow_color = '#'.$this->wm_shadow_color;
			}
		}

		if ($this->wm_overlay_path != '')
		{
			$this->wm_overlay_path = str_replace("\\", "/", realpath($this->wm_overlay_path));
		}

		if ($this->wm_shadow_color != '')
		{
			$this->wm_use_drop_shadow = TRUE;
		}

		if ($this->wm_font_path != '')
		{
			$this->wm_use_truetype = TRUE;
		}

		return TRUE;
	}

	
	function resize()
	{
		$protocol = 'image_process_'.$this->image_library;

		if (preg_match('/gd2$/i', $protocol))
		{
			$protocol = 'image_process_gd';
		}

		return $this->$protocol('resize');
	}

	
	function crop()
	{
		$protocol = 'image_process_'.$this->image_library;

		if (preg_match('/gd2$/i', $protocol))
		{
			$protocol = 'image_process_gd';
		}

		return $this->$protocol('crop');
	}

	
	function rotate()
	{
		
		$degs = array(90, 180, 270, 'vrt', 'hor');

		if ($this->rotation_angle == '' OR ! in_array($this->rotation_angle, $degs))
		{
			$this->set_error('imglib_rotation_angle_required');
			return FALSE;	
		}

		
		if ($this->rotation_angle == 90 OR $this->rotation_angle == 270)
		{
			$this->width	= $this->orig_height;
			$this->height	= $this->orig_width;
		}
		else
		{
			$this->width	= $this->orig_width;
			$this->height	= $this->orig_height;
		}


		
		if ($this->image_library == 'imagemagick' OR $this->image_library == 'netpbm')
		{
			$protocol = 'image_process_'.$this->image_library;

			return $this->$protocol('rotate');
		}

		if ($this->rotation_angle == 'hor' OR $this->rotation_angle == 'vrt')
		{
			return $this->image_mirror_gd();
		}
		else
		{
			return $this->image_rotate_gd();
		}
	}

	
	function image_process_gd($action = 'resize')
	{
		$v2_override = FALSE;

		
		if ($this->dynamic_output === FALSE)
		{
			if ($this->orig_width == $this->width AND $this->orig_height == $this->height)
			{
				if ($this->source_image != $this->new_image)
				{
					if (@copy($this->full_src_path, $this->full_dst_path))
					{
						@chmod($this->full_dst_path, FILE_WRITE_MODE);
					}
				}

				return TRUE;
			}
		}

		
		if ($action == 'crop')
		{
			
			$this->orig_width  = $this->width;
			$this->orig_height = $this->height;

			
			if ($this->gd_version() !== FALSE)
			{
				$gd_version = str_replace('0', '', $this->gd_version());
				$v2_override = ($gd_version == 2) ? TRUE : FALSE;
			}
		}
		else
		{
			
			$this->x_axis = 0;
			$this->y_axis = 0;
		}

		
		if ( ! ($src_img = $this->image_create_gd()))
		{
			return FALSE;
		}

		
		if ($this->image_library == 'gd2' AND function_exists('imagecreatetruecolor'))
		{
			$create	= 'imagecreatetruecolor';
			$copy	= 'imagecopyresampled';
		}
		else
		{
			$create	= 'imagecreate';
			$copy	= 'imagecopyresized';
		}

		$dst_img = $create($this->width, $this->height);

		if ($this->image_type == 3) 
		{
			imagealphablending($dst_img, FALSE);
			imagesavealpha($dst_img, TRUE);
		}

		$copy($dst_img, $src_img, 0, 0, $this->x_axis, $this->y_axis, $this->width, $this->height, $this->orig_width, $this->orig_height);

		
		if ($this->dynamic_output == TRUE)
		{
			$this->image_display_gd($dst_img);
		}
		else
		{
			
			if ( ! $this->image_save_gd($dst_img))
			{
				return FALSE;
			}
		}

		
		imagedestroy($dst_img);
		imagedestroy($src_img);

		
		@chmod($this->full_dst_path, FILE_WRITE_MODE);

		return TRUE;
	}

	
	function image_process_imagemagick($action = 'resize')
	{
		
		if ($this->library_path == '')
		{
			$this->set_error('imglib_libpath_invalid');
			return FALSE;
		}

		if ( ! preg_match("/convert$/i", $this->library_path))
		{
			$this->library_path = rtrim($this->library_path, '/').'/';

			$this->library_path .= 'convert';
		}

		
		$cmd = $this->library_path." -quality ".$this->quality;

		if ($action == 'crop')
		{
			$cmd .= " -crop ".$this->width."x".$this->height."+".$this->x_axis."+".$this->y_axis." \"$this->full_src_path\" \"$this->full_dst_path\" 2>&1";
		}
		elseif ($action == 'rotate')
		{
			switch ($this->rotation_angle)
			{
				case 'hor'	: $angle = '-flop';
					break;
				case 'vrt'	: $angle = '-flip';
					break;
				default		: $angle = '-rotate '.$this->rotation_angle;
					break;
			}

			$cmd .= " ".$angle." \"$this->full_src_path\" \"$this->full_dst_path\" 2>&1";
		}
		else 
		{
			$cmd .= " -resize ".$this->width."x".$this->height." \"$this->full_src_path\" \"$this->full_dst_path\" 2>&1";
		}

		$retval = 1;

		@exec($cmd, $output, $retval);

		
		if ($retval > 0)
		{
			$this->set_error('imglib_image_process_failed');
			return FALSE;
		}

		
		@chmod($this->full_dst_path, FILE_WRITE_MODE);

		return TRUE;
	}


	function image_process_netpbm($action = 'resize')
	{
		if ($this->library_path == '')
		{
			$this->set_error('imglib_libpath_invalid');
			return FALSE;
		}

		
		switch ($this->image_type)
		{
			case 1 :
						$cmd_in		= 'giftopnm';
						$cmd_out	= 'ppmtogif';
				break;
			case 2 :
						$cmd_in		= 'jpegtopnm';
						$cmd_out	= 'ppmtojpeg';
				break;
			case 3 :
						$cmd_in		= 'pngtopnm';
						$cmd_out	= 'ppmtopng';
				break;
		}

		if ($action == 'crop')
		{
			$cmd_inner = 'pnmcut -left '.$this->x_axis.' -top '.$this->y_axis.' -width '.$this->width.' -height '.$this->height;
		}
		elseif ($action == 'rotate')
		{
			switch ($this->rotation_angle)
			{
				case 90		:	$angle = 'r270';
					break;
				case 180	:	$angle = 'r180';
					break;
				case 270	:	$angle = 'r90';
					break;
				case 'vrt'	:	$angle = 'tb';
					break;
				case 'hor'	:	$angle = 'lr';
					break;
			}

			$cmd_inner = 'pnmflip -'.$angle.' ';
		}
		else 
		{
			$cmd_inner = 'pnmscale -xysize '.$this->width.' '.$this->height;
		}

		$cmd = $this->library_path.$cmd_in.' '.$this->full_src_path.' | '.$cmd_inner.' | '.$cmd_out.' > '.$this->dest_folder.'netpbm.tmp';

		$retval = 1;

		@exec($cmd, $output, $retval);

		
		if ($retval > 0)
		{
			$this->set_error('imglib_image_process_failed');
			return FALSE;
		}

		
		copy ($this->dest_folder.'netpbm.tmp', $this->full_dst_path);
		unlink ($this->dest_folder.'netpbm.tmp');
		@chmod($this->full_dst_path, FILE_WRITE_MODE);

		return TRUE;
	}

	
	function image_rotate_gd()
	{
		
		if ( ! ($src_img = $this->image_create_gd()))
		{
			return FALSE;
		}

		

		$white	= imagecolorallocate($src_img, 255, 255, 255);

		
		$dst_img = imagerotate($src_img, $this->rotation_angle, $white);

		
		if ($this->dynamic_output == TRUE)
		{
			$this->image_display_gd($dst_img);
		}
		else
		{
			
			if ( ! $this->image_save_gd($dst_img))
			{
				return FALSE;
			}
		}

		
		imagedestroy($dst_img);
		imagedestroy($src_img);

		

		@chmod($this->full_dst_path, FILE_WRITE_MODE);

		return TRUE;
	}

	
	function image_mirror_gd()
	{
		if ( ! $src_img = $this->image_create_gd())
		{
			return FALSE;
		}

		$width  = $this->orig_width;
		$height = $this->orig_height;

		if ($this->rotation_angle == 'hor')
		{
			for ($i = 0; $i < $height; $i++)
			{
				$left  = 0;
				$right = $width-1;

				while ($left < $right)
				{
					$cl = imagecolorat($src_img, $left, $i);
					$cr = imagecolorat($src_img, $right, $i);

					imagesetpixel($src_img, $left, $i, $cr);
					imagesetpixel($src_img, $right, $i, $cl);

					$left++;
					$right--;
				}
			}
		}
		else
		{
			for ($i = 0; $i < $width; $i++)
			{
				$top = 0;
				$bot = $height-1;

				while ($top < $bot)
				{
					$ct = imagecolorat($src_img, $i, $top);
					$cb = imagecolorat($src_img, $i, $bot);

					imagesetpixel($src_img, $i, $top, $cb);
					imagesetpixel($src_img, $i, $bot, $ct);

					$top++;
					$bot--;
				}
			}
		}

		
		if ($this->dynamic_output == TRUE)
		{
			$this->image_display_gd($src_img);
		}
		else
		{
			
			if ( ! $this->image_save_gd($src_img))
			{
				return FALSE;
			}
		}

		
		imagedestroy($src_img);

		
		@chmod($this->full_dst_path, FILE_WRITE_MODE);

		return TRUE;
	}

	
	function watermark()
	{
		if ($this->wm_type == 'overlay')
		{
			return $this->overlay_watermark();
		}
		else
		{
			return $this->text_watermark();
		}
	}

	
	function overlay_watermark()
	{
		if ( ! function_exists('imagecolortransparent'))
		{
			$this->set_error('imglib_gd_required');
			return FALSE;
		}

		
		$this->get_image_properties();

		
		$props			= $this->get_image_properties($this->wm_overlay_path, TRUE);
		$wm_img_type	= $props['image_type'];
		$wm_width		= $props['width'];
		$wm_height		= $props['height'];

		
		$wm_img  = $this->image_create_gd($this->wm_overlay_path, $wm_img_type);
		$src_img = $this->image_create_gd($this->full_src_path);

		

		$this->wm_vrt_alignment = strtoupper(substr($this->wm_vrt_alignment, 0, 1));
		$this->wm_hor_alignment = strtoupper(substr($this->wm_hor_alignment, 0, 1));

		if ($this->wm_vrt_alignment == 'B')
			$this->wm_vrt_offset = $this->wm_vrt_offset * -1;

		if ($this->wm_hor_alignment == 'R')
			$this->wm_hor_offset = $this->wm_hor_offset * -1;

		
		$x_axis = $this->wm_hor_offset + $this->wm_padding;
		$y_axis = $this->wm_vrt_offset + $this->wm_padding;

		
		switch ($this->wm_vrt_alignment)
		{
			case 'T':
				break;
			case 'M':	$y_axis += ($this->orig_height / 2) - ($wm_height / 2);
				break;
			case 'B':	$y_axis += $this->orig_height - $wm_height;
				break;
		}

		
		switch ($this->wm_hor_alignment)
		{
			case 'L':
				break;
			case 'C':	$x_axis += ($this->orig_width / 2) - ($wm_width / 2);
				break;
			case 'R':	$x_axis += $this->orig_width - $wm_width;
				break;
		}

		
		if ($wm_img_type == 3 AND function_exists('imagealphablending'))
		{
			@imagealphablending($src_img, TRUE);
		}

		
		$rgba = imagecolorat($wm_img, $this->wm_x_transp, $this->wm_y_transp);
		$alpha = ($rgba & 0x7F000000) >> 24;

		
		if ($alpha > 0)
		{
			
			imagecopy($src_img, $wm_img, $x_axis, $y_axis, 0, 0, $wm_width, $wm_height);
		}
		else
		{
			
			imagecolortransparent($wm_img, imagecolorat($wm_img, $this->wm_x_transp, $this->wm_y_transp));
			imagecopymerge($src_img, $wm_img, $x_axis, $y_axis, 0, 0, $wm_width, $wm_height, $this->wm_opacity);
		}

		
		if ($this->dynamic_output == TRUE)
		{
			$this->image_display_gd($src_img);
		}
		else
		{
			if ( ! $this->image_save_gd($src_img))
			{
				return FALSE;
			}
		}

		imagedestroy($src_img);
		imagedestroy($wm_img);

		return TRUE;
	}

	
	function text_watermark()
	{
		if ( ! ($src_img = $this->image_create_gd()))
		{
			return FALSE;
		}

		if ($this->wm_use_truetype == TRUE AND ! file_exists($this->wm_font_path))
		{
			$this->set_error('imglib_missing_font');
			return FALSE;
		}

		
		$this->get_image_properties();

		
		$this->wm_font_color	= str_replace('#', '', $this->wm_font_color);
		$this->wm_shadow_color	= str_replace('#', '', $this->wm_shadow_color);

		$R1 = hexdec(substr($this->wm_font_color, 0, 2));
		$G1 = hexdec(substr($this->wm_font_color, 2, 2));
		$B1 = hexdec(substr($this->wm_font_color, 4, 2));

		$R2 = hexdec(substr($this->wm_shadow_color, 0, 2));
		$G2 = hexdec(substr($this->wm_shadow_color, 2, 2));
		$B2 = hexdec(substr($this->wm_shadow_color, 4, 2));

		$txt_color	= imagecolorclosest($src_img, $R1, $G1, $B1);
		$drp_color	= imagecolorclosest($src_img, $R2, $G2, $B2);

		

		if ($this->wm_vrt_alignment == 'B')
			$this->wm_vrt_offset = $this->wm_vrt_offset * -1;

		if ($this->wm_hor_alignment == 'R')
			$this->wm_hor_offset = $this->wm_hor_offset * -1;

		
		if ($this->wm_use_truetype == TRUE)
		{
			if ($this->wm_font_size == '')
				$this->wm_font_size = '17';

			$fontwidth  = $this->wm_font_size-($this->wm_font_size/4);
			$fontheight = $this->wm_font_size;
			$this->wm_vrt_offset += $this->wm_font_size;
		}
		else
		{
			$fontwidth  = imagefontwidth($this->wm_font_size);
			$fontheight = imagefontheight($this->wm_font_size);
		}

		
		$x_axis = $this->wm_hor_offset + $this->wm_padding;
		$y_axis = $this->wm_vrt_offset + $this->wm_padding;

		
		if ($this->wm_use_drop_shadow == FALSE)
			$this->wm_shadow_distance = 0;

		$this->wm_vrt_alignment = strtoupper(substr($this->wm_vrt_alignment, 0, 1));
		$this->wm_hor_alignment = strtoupper(substr($this->wm_hor_alignment, 0, 1));

		switch ($this->wm_vrt_alignment)
		{
			case	 "T" :
				break;
			case "M":	$y_axis += ($this->orig_height/2)+($fontheight/2);
				break;
			case "B":	$y_axis += ($this->orig_height - $fontheight - $this->wm_shadow_distance - ($fontheight/2));
				break;
		}

		$x_shad = $x_axis + $this->wm_shadow_distance;
		$y_shad = $y_axis + $this->wm_shadow_distance;

		
		switch ($this->wm_hor_alignment)
		{
			case "L":
				break;
			case "R":
						if ($this->wm_use_drop_shadow)
							$x_shad += ($this->orig_width - $fontwidth*strlen($this->wm_text));
							$x_axis += ($this->orig_width - $fontwidth*strlen($this->wm_text));
				break;
			case "C":
						if ($this->wm_use_drop_shadow)
							$x_shad += floor(($this->orig_width - $fontwidth*strlen($this->wm_text))/2);
							$x_axis += floor(($this->orig_width  -$fontwidth*strlen($this->wm_text))/2);
				break;
		}

		
		if ($this->wm_use_truetype)
		{
			if ($this->wm_use_drop_shadow)
				imagettftext($src_img, $this->wm_font_size, 0, $x_shad, $y_shad, $drp_color, $this->wm_font_path, $this->wm_text);
				imagettftext($src_img, $this->wm_font_size, 0, $x_axis, $y_axis, $txt_color, $this->wm_font_path, $this->wm_text);
		}
		else
		{
			if ($this->wm_use_drop_shadow)
				imagestring($src_img, $this->wm_font_size, $x_shad, $y_shad, $this->wm_text, $drp_color);
				imagestring($src_img, $this->wm_font_size, $x_axis, $y_axis, $this->wm_text, $txt_color);
		}

		
		if ($this->dynamic_output == TRUE)
		{
			$this->image_display_gd($src_img);
		}
		else
		{
			$this->image_save_gd($src_img);
		}

		imagedestroy($src_img);

		return TRUE;
	}

	
	function image_create_gd($path = '', $image_type = '')
	{
		if ($path == '')
			$path = $this->full_src_path;

		if ($image_type == '')
			$image_type = $this->image_type;


		switch ($image_type)
		{
			case	 1 :
						if ( ! function_exists('imagecreatefromgif'))
						{
							$this->set_error(array('imglib_unsupported_imagecreate', 'imglib_gif_not_supported'));
							return FALSE;
						}

						return imagecreatefromgif($path);
				break;
			case 2 :
						if ( ! function_exists('imagecreatefromjpeg'))
						{
							$this->set_error(array('imglib_unsupported_imagecreate', 'imglib_jpg_not_supported'));
							return FALSE;
						}

						return imagecreatefromjpeg($path);
				break;
			case 3 :
						if ( ! function_exists('imagecreatefrompng'))
						{
							$this->set_error(array('imglib_unsupported_imagecreate', 'imglib_png_not_supported'));
							return FALSE;
						}

						return imagecreatefrompng($path);
				break;

		}

		$this->set_error(array('imglib_unsupported_imagecreate'));
		return FALSE;
	}

	
	function image_save_gd($resource)
	{
		switch ($this->image_type)
		{
			case 1 :
						if ( ! function_exists('imagegif'))
						{
							$this->set_error(array('imglib_unsupported_imagecreate', 'imglib_gif_not_supported'));
							return FALSE;
						}

						if ( ! @imagegif($resource, $this->full_dst_path))
						{
							$this->set_error('imglib_save_failed');
							return FALSE;
						}
				break;
			case 2	:
						if ( ! function_exists('imagejpeg'))
						{
							$this->set_error(array('imglib_unsupported_imagecreate', 'imglib_jpg_not_supported'));
							return FALSE;
						}

						if ( ! @imagejpeg($resource, $this->full_dst_path, $this->quality))
						{
							$this->set_error('imglib_save_failed');
							return FALSE;
						}
				break;
			case 3	:
						if ( ! function_exists('imagepng'))
						{
							$this->set_error(array('imglib_unsupported_imagecreate', 'imglib_png_not_supported'));
							return FALSE;
						}

						if ( ! @imagepng($resource, $this->full_dst_path))
						{
							$this->set_error('imglib_save_failed');
							return FALSE;
						}
				break;
			default		:
							$this->set_error(array('imglib_unsupported_imagecreate'));
							return FALSE;
				break;
		}

		return TRUE;
	}

	
	function image_display_gd($resource)
	{
		header("Content-Disposition: filename={$this->source_image};");
		header("Content-Type: {$this->mime_type}");
		header('Content-Transfer-Encoding: binary');
		header('Last-Modified: '.gmdate('D, d M Y H:i:s', time()).' GMT');

		switch ($this->image_type)
		{
			case 1		:	imagegif($resource);
				break;
			case 2		:	imagejpeg($resource, '', $this->quality);
				break;
			case 3		:	imagepng($resource);
				break;
			default		:	echo 'Unable to display the image';
				break;
		}
	}

	
	function image_reproportion()
	{
		if ( ! is_numeric($this->width) OR ! is_numeric($this->height) OR $this->width == 0 OR $this->height == 0)
			return;

		if ( ! is_numeric($this->orig_width) OR ! is_numeric($this->orig_height) OR $this->orig_width == 0 OR $this->orig_height == 0)
			return;

		$new_width	= ceil($this->orig_width*$this->height/$this->orig_height);
		$new_height	= ceil($this->width*$this->orig_height/$this->orig_width);

		$ratio = (($this->orig_height/$this->orig_width) - ($this->height/$this->width));

		if ($this->master_dim != 'width' AND $this->master_dim != 'height')
		{
			$this->master_dim = ($ratio < 0) ? 'width' : 'height';
		}

		if (($this->width != $new_width) AND ($this->height != $new_height))
		{
			if ($this->master_dim == 'height')
			{
				$this->width = $new_width;
			}
			else
			{
				$this->height = $new_height;
			}
		}
	}

	
	function get_image_properties($path = '', $return = FALSE)
	{
		

		if ($path == '')
			$path = $this->full_src_path;

		if ( ! file_exists($path))
		{
			$this->set_error('imglib_invalid_path');
			return FALSE;
		}

		$vals = @getimagesize($path);

		$types = array(1 => 'gif', 2 => 'jpeg', 3 => 'png');

		$mime = (isset($types[$vals['2']])) ? 'image/'.$types[$vals['2']] : 'image/jpg';

		if ($return == TRUE)
		{
			$v['width']			= $vals['0'];
			$v['height']		= $vals['1'];
			$v['image_type']	= $vals['2'];
			$v['size_str']		= $vals['3'];
			$v['mime_type']		= $mime;

			return $v;
		}

		$this->orig_width	= $vals['0'];
		$this->orig_height	= $vals['1'];
		$this->image_type	= $vals['2'];
		$this->size_str		= $vals['3'];
		$this->mime_type	= $mime;

		return TRUE;
	}

	
	function size_calculator($vals)
	{
		if ( ! is_array($vals))
		{
			return;
		}

		$allowed = array('new_width', 'new_height', 'width', 'height');

		foreach ($allowed as $item)
		{
			if ( ! isset($vals[$item]) OR $vals[$item] == '')
				$vals[$item] = 0;
		}

		if ($vals['width'] == 0 OR $vals['height'] == 0)
		{
			return $vals;
		}

		if ($vals['new_width'] == 0)
		{
			$vals['new_width'] = ceil($vals['width']*$vals['new_height']/$vals['height']);
		}
		elseif ($vals['new_height'] == 0)
		{
			$vals['new_height'] = ceil($vals['new_width']*$vals['height']/$vals['width']);
		}

		return $vals;
	}

	
	function explode_name($source_image)
	{
		$ext = strrchr($source_image, '.');
		$name = ($ext === FALSE) ? $source_image : substr($source_image, 0, -strlen($ext));

		return array('ext' => $ext, 'name' => $name);
	}

	
	function gd_loaded()
	{
		if ( ! extension_loaded('gd'))
		{
			if ( ! dl('gd.so'))
			{
				return FALSE;
			}
		}

		return TRUE;
	}

	
	function gd_version()
	{
		if (function_exists('gd_info'))
		{
			$gd_version = @gd_info();
			$gd_version = preg_replace("/\D/", "", $gd_version['GD Version']);

			return $gd_version;
		}

		return FALSE;
	}

	
	function set_error($msg)
	{
		$CI =& get_instance();
		$CI->lang->load('imglib');

		if (is_array($msg))
		{
			foreach ($msg as $val)
			{

				$msg = ($CI->lang->line($val) == FALSE) ? $val : $CI->lang->line($val);
				$this->error_msg[] = $msg;
				log_message('error', $msg);
			}
		}
		else
		{
			$msg = ($CI->lang->line($msg) == FALSE) ? $msg : $CI->lang->line($msg);
			$this->error_msg[] = $msg;
			log_message('error', $msg);
		}
	}

	
	function display_errors($open = '<p>', $close = '</p>')
	{
		$str = '';
		foreach ($this->error_msg as $val)
		{
			$str .= $open.$val.$close;
		}

		return $str;
	}

}
