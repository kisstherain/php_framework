<?php
return array(
	"BASEPATH"=>__dir__."/",
	"ENVIRONMENT"=>"development",
	"PUBLICPATH"=>"/public/",
	"CSSPATH"=>"/public/css/",
	"JSPATH"=>"/public/js/",
	"IMGPATH"=>"/public/img/",
	"UPLOADPATH"=>__dir__."/upload/",
	"CSSVERSION"=>"123",
	"JSVERSION"=>"123",
	"ROUTER"=>array(
		"default"=>array(
			"path"=>realpath("apps")."/",
			"router"=>require("apps/config/routers.php")
		),
	)
);
