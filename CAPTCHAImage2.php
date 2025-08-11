<?php
//barkng-captcha
//Area for random
//x from 25 to 375
//y from 25 to 225
//On perimeter - x or y should be limited to its axis

//generate 4 random symbols: 0-9 A-Z = chance to guess 1 in 1679616 
//but with lines it will be less, like 1296, i'm lazy too figure out it

//We need 4 points total:

function random_point(){
	$stick=0;
	//stick to x or y
	$stick=mt_rand(0,1);
	$symbol_type=mt_rand(0,1);
	if ($stick==0){
	return [20+(mt_rand(0,1)*(350+5+2)),mt_rand(25,225),(mt_rand(17,42)*$symbol_type)+(mt_rand(0,9)*(1-$symbol_type)),mt_rand(0,1)];	
	}
	else{
	return [mt_rand(25,375),18+(mt_rand(0,1)*(200+5+2)),(mt_rand(17,42)*$symbol_type)+(mt_rand(0,9)*(1-$symbol_type)),mt_rand(0,1)];		
	}
	
}

$points=[random_point(),random_point(),random_point(),random_point()];

// Create a new Imagick object
$imagick = new Imagick();
// Create a new image with a white background (width, height, background color)
$imagick->newImage(400, 250, new ImagickPixel('#289ed7'));
// Set the image format to PNG
$imagick->setImageFormat('png');


$fontPath = 'consola.ttf';
$fontSize = 36;
$fontColor = 'black';

// Create a new ImagickDraw object
$draw = new ImagickDraw();

$draw->setFont($fontPath);
$draw->setFontSize($fontSize);
$draw->setFillColor($fontColor);


$draw->setFillColor(new ImagickPixel('black'));



	$draw->setFillColor(new ImagickPixel('#289ed7'));

	
	foreach ($points as $v){

	// Draw the text
// Function to fill color
$draw->setFillColor('rgba(255, 255, 255, 0)');
$draw->setStrokeColor('white');
$draw->setStrokeWidth(1);
if($v[3]==1){
$draw->setStrokeDashArray([10, 10]); // Example:  5 pixels dash, 3 pixels gap
}
else{
$draw->setStrokeDashArray([5, 10]); // Example:  5 pixels dash, 3 pixels gap	
}
// Function to draw line
$draw->line($v[0], $v[1], 200+mt_rand(-70,70), 125+mt_rand(-50,50));

	}	


foreach ($points as $v){

$draw->setFillColor('black');
$draw->setStrokeColor('rgba(0,0,0,0)');
$draw->setStrokeWidth(0);
	// Draw the text
$draw->annotation($v[0], $v[1]+7,  chr($v[2]+48));

	}

$imagick->drawImage($draw);	

		
$points = array( 
                0,mt_rand(0,5)*(-10), mt_rand(0,1)*50,mt_rand(0,1)*50, # top left  
                400,0, 400-(mt_rand(1,5)*10),+mt_rand(1,2)*20, # top right
                0,250, 0,250-mt_rand(0,50), # bottom left 
                400,250, 400,200+(mt_rand(0,2)*10) # bottom right
                );
				

$imagick->setimagebackgroundcolor("#289ed7");
$imagick->setImageMatte(true);
$imagick->setImageVirtualPixelMethod(\Imagick::VIRTUALPIXELMETHOD_BACKGROUND);

/* distortImage */
$imagick->distortImage(\Imagick::DISTORTION_PERSPECTIVE, $points, false);


$imagick->waveImage(mt_rand(2,6), 70+mt_rand(0,60));
$imagick->waveImage(-1*mt_rand(2,4), mt_rand(0,10)+30);
$imagick->cropImage(400, 250, 0, 0);

$draw->setFontSize(20);
$draw->setFont('arialbd.ttf');
$draw->setFillColor('rgba(0, 0, 0, 0.5)');
$imagick->annotateImage($draw, 40, 50, 0, "Символы в порядке увеличения\r\n            Длинный пунктир\r\n                  0-9 A-Z");
// Set the content type header for image output
header('Content-Type: image/png');
// Output the image blob to the browser
echo $imagick->getImageBlob();

// Destroy the Imagick object to free up resources (optional but good practice)
$imagick->destroy();


?>

