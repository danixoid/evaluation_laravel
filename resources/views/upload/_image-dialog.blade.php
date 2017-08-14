<html>
<head>
	<meta charset="UTF-8">
	<title>Image Upload Dialog</title>
	<link href="{!! asset("css/app.css") !!}" rel="stylesheet">
</head>
<body>
<div class="container">
	<div class="row col-md-10 col-md-offset-1">
		<div id="upload_form">
			<p>
				<!-- Change the url here to reflect your image handling controller -->
				<form action="{!! route('upload.form.image') !!}" method="POST"
					  enctype="multipart/form-data" target="upload_target">
					{!! csrf_field() !!}
					<input type="file" class="form-control" name="imagefile"
						   onchange="this.form.submit(); ImageUpload.inProgress();">
				</form>
			</p>
		</div>
		<div id="image_preview" style="display:none; font-style: helvetica, arial;">
			<iframe frameborder=0 scrolling="no" id="upload_target" name="upload_target" height=240 width=320></iframe>
		</div>
	</div>
	<script type="text/javascript" src="{!! asset("js/tinymce/plugins/imageupload/upload.js") !!}"></script>
</div>
</body>
</html>

