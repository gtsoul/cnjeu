/**
 * iCagenda iCtip 2.1.1
 * © 2013 JoomliC
 *
 * update	2013-03-14
 *
 */

var icmouse;
var icclasstip;
var icclass;
var posit;

jQuery(function ($){
		if (posit=='left') {
			$width='390px';
			$pos=$(icclass).offset().left -450+'px';
		}
		if (posit=='right') {
			$width='390px';
			$pos=$(icclass).offset().left+$(icclass).width()+10+'px';
		if (posit=='center') {
			$width='25%';
			$pos='36%';
		if (posit=='topcenter') {
			$width='25%';
			$pos='36%';
		if (posit=='bottomcenter') {
			$width='25%';
			$pos='36%';