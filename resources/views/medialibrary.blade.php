<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>File Upload</title>
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    
    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <!-- Styles -->
   
   <style>

.drag-text {
  text-align: center;
}

.drag-text h4 {
  font-weight: 100;
  text-transform: uppercase;
  color: #002b5c;
  padding: 60px 0;
}
.file-upload-image {
  max-height: 100px;
  max-width: 100px;
  margin: auto;
  padding: 20px;
}

.remove-image {
  width: 200px;
  margin: 0;
  color: #fff;
  background: #cd4535;
  border: none;
  padding: 10px;
  border-radius: 4px;
  border-bottom: 4px solid #b02818;
  transition: all 0.2s ease;
  outline: none;
  text-transform: uppercase;
  font-weight: 700;
}

.remove-image:hover {
  background: #c13b2a;
  color: #ffffff;
  transition: all 0.2s ease;
  cursor: pointer;
}
.file-upload {
  background-color: #ffffff;
  width: 400px;
  padding: 10px 0 30px 0;
  border-radius: 3px;
}

.file-upload-btn {
  width: 100%;
  margin: 0;
  color: #fff;
  background: #1fb264;
  border: none;
  padding: 10px;
  border-radius: 4px;
  border-bottom: 4px solid #15824b;
  transition: all 0.2s ease;
  outline: none;
  text-transform: uppercase;
  font-weight: 700;
}

.file-upload-btn:hover {
  background: #1aa059;
  color: #ffffff;
  transition: all 0.2s ease;
  cursor: pointer;
}

.file-upload-btn:active {
  border: 0;
  transition: all 0.2s ease;
}

.file-upload-content {
  display: none;
  text-align: center;
  border: 1px dashed #002b5c;
  position: relative;
  border-radius: 5px;
}

.file-upload-input {
  position: absolute;
  margin: 0;
  padding: 0;
  width: 100%;
  height: 100%;
  outline: none;
  opacity: 0;
  cursor: pointer;
}

.image-upload-wrap {
  border: 1px dashed #002b5c;
  position: relative;
  border-radius: 5px;
}

.image-title-wrap {
  padding: 0 15px 15px 15px;
  color: #222;
}

   </style>

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    File Upload
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </nav>
        <main class="py-4">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="col-md-12 mb-4">
                            <button type="button" class="btn btn-primary btn-ico" data-toggle="modal" data-target="#uploaderModal"><i class="fa fa-files-o"></i>File Upload</button>
                        </div>
                    </div>
                </div>
            </div>
                            <!-- Modal -->
                <div class="modal fade" id="uploaderModal" tabindex="-1" aria-labelledby="uploaderModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="uploaderModalLabel">Import File</h5>  
                            </div>
                            <div class="modal-body dropzone">   
                                <form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="image-upload-wrap">
                                        <input name="file" class="bulk-search-validate file-upload-input" type='file' accept=".csv,.xls" onchange="readURL(this);" required />
                                            <div class="drag-text">
                                              <h4>Drag and drop a csv or xls file</h4>
                                            </div>
                                    </div>
                                    <div class="file-upload-content">
                                        <img class="file-upload-image" src="{{asset('/img/file-icon.jpg')}}" width="100" height="100" alt="your image"/>
                                        <div class="image-title-wrap">
                                             <button type="button" onclick="removeUpload()" class="remove-image">
                                             Remove <span class="image-title">Uploaded Image</span></button>
                                        </div>
                                    </div>
                                    <div class="modal-footer mt-2 mb-2">
                                        <button type="submit" class="btn btn-success">Upload</button>
                                        <button type="button" class="btn btn-info" data-bs-dismiss="modal">Close</button>
                                        
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
        </main>
        <script>
            function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('.image-upload-wrap').hide();
            $('.file-upload-content').show();
            $('.image-title').html(input.files[0].name);
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        removeUpload();
    }
}

// Deathcheck | Remove Image With Preview
function removeUpload() {
            $('.file-upload-input').replaceWith($('.file-upload-input').clone());
            $('.file-upload-content').hide();
            $('.image-upload-wrap').show();
        }

        $('.image-upload-wrap').bind('dragover', function() {
            $('.image-upload-wrap').addClass('image-dropping');
        });
        $('.image-upload-wrap').bind('dragleave', function() {
            $('.image-upload-wrap').removeClass('image-dropping');
        });
        </script>

</body>
</html>
