<html>
<head>
	<meta charset="UTF-8">
	<title>Image Upload</title>
	<script type="text/javascript" src="{!! asset("js/tinymce/plugins/imageupload/upload.js") !!}"></script>
	<script type="text/javascript">
	window.parent.window.ImageUpload.uploadSuccess({
		code : '{!! route('uploaded.image',$filename) !!}'
	});
	</script>
	<style type="text/css">
		img {
			max-height: 240px;
			max-width: 320px;
		}
	</style>
</head>
<body>
	<img src="{!! route('uploaded.image',$filename) !!}">
</body>
</html>