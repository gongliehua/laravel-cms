<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('bower_components/Ionicons/css/ionicons.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('bower_components/select2/dist/css/select2.min.css') }}">
    <!-- toastr -->
    <link rel="stylesheet" href="{{ asset('bower_components/toastr/build/toastr.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/AdminLTE.min.css') }}">
    <!-- umeditor -->
    <link href="{{ asset('umeditor/themes/default/css/umeditor.css') }}" type="text/css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">

<section class="content-header">
    <h1>
        添加文章
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="javascript:void(0)" onclick="top.location.href='{{ url('admin/index') }}'"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li><a href="javascript:void(0)">文章管理</a>></li>
        <li><a href="{{ url('admin/article') }}">文章列表</a></li>
        <li class="active">添加文章</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <!-- form start -->
                <form role="form" method="post" enctype="multipart/form-data" onsubmit="if(!UM.getEditor('myEditor').hasContents()){alert('内容不能为空');return false;}">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="title">标题</label> @if ($errors->has('title')) {{ $errors->first('title') }} @endif
                            <input type="text" name="title" class="form-control" id="title" value="{{ old('title') }}" placeholder="" autocomplete="off" required>
                        </div>
                        <div class="form-group">
                            <label>所属栏目</label> @if ($errors->has('category_id')) {{ $errors->first('category_id') }} @endif
                            <select name="category_id" class="form-control select2" style="width: 100%;">
                                @if (count($categorys) > 0)
                                    @foreach ($categorys as $key=>$value)
                                        <option value="{{ $value->id }}"> @if ($value->parent_id === 0) ｜ @endif {{ str_repeat('－',$value->level*4) }} {{ $value->name }}</option>
                                    @endforeach
                                @else
                                    <option value="0">｜暂无栏目</option>
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="keywords">关键字</label> @if ($errors->has('keywords')) {{ $errors->first('keywords') }} @endif
                            <input type="text" name="keywords" class="form-control" id="keywords" value="{{ old('keywords') }}" placeholder="" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="description">描述</label> @if ($errors->has('description')) {{ $errors->first('description') }} @endif
                            <textarea class="form-control" name="description" id="description" rows="3" placeholder="">{{ old('description') }}</textarea>
                        </div>
                        <div class="form-group" style="overflow: auto;">
                            <label for="content">内容</label> @if ($errors->has('content')) {{ $errors->first('content') }} @endif
                            <script type="text/plain" id="myEditor" name="content" style="width:100%;height:240px;"></script>
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-primary">提交</button>
                    </div>
                </form>
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col-md-12 -->
    </div>
    <!-- /.row -->
</section>

<!-- jQuery 3 -->
<script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- umeditor -->
<script src="{{ asset('umeditor/umeditor.config.js') }}"></script>
<script src="{{ asset('umeditor/umeditor.js') }}"></script>
<!-- toastr -->
<script src="{{ asset('bower_components/toastr/build/toastr.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('bower_components/select2/dist/js/select2.full.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<!-- Diy js -->
<script>
    var um = UM.getEditor('myEditor');

    $(function(){
        $('.select2').select2();
    });

    function uploadImg(file,imgTag){
        var img = document.getElementById(imgTag);
        if(file.files && file.files[0]) {
            var reader = new FileReader();
            reader.onload = function (evt) {
                img.src = evt.target.result;
            };
            reader.readAsDataURL(file.files[0]);
        } else {
            img.src = '{{ asset('dist/img/user2-160x160.jpg') }}';
        }
    };

    toastr.options = {closeButton: true,progressBar: true};
    $(function () {
        @if ($errors->has('success'))
        toastr.success('{{ $errors->first('success') }}');
        @endif
        @if ($errors->has('error'))
        toastr.error('{{ $errors->first('error') }}');
        @endif

    });
</script>

</body>
</html>
