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

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <style>
        ul.pagination {margin:0;}
        tr:hover {background-color: #f5f5f5;}
    </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">

<section class="content-header">
    <h1>
        配置管理
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="javascript:void(0)" onclick="top.location.href='{{ url('admin/index') }}'"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li><a href="javascript:void(0)">系统管理</a></li>
        <li class="active">配置管理</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <a class="btn btn-sm btn-primary grid-refresh" title="刷新" onclick="location.reload()"><i class="fa fa-refresh"></i><span class="hidden-xs"> 刷新</span></a>
                    {{--<a href="{{ url('admin/addConfig') }}" class="btn btn-sm btn-success" title="新增"><i class="fa fa-plus"></i><span class="hidden-xs"> 新增</span></a>--}}
                </div>
                <!-- /.box-header -->
                <div class="box-body" style="overflow-x: auto;overflow-y: auto;">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 200px">配置标题</th>
                            <th>配置值</th>
                        </tr>
                        @if (count($configs) > 0)
                            <form action="" method="post">
                                @foreach ($configs as $key=>$value)
                                    <tr>
                                        <td>{{ $value->title }}</td>
                                        @switch($value->type)
                                            @case(1)
                                            <td><input type="text" name="name[{{ $value->name }}]" class="form-control" value="{{ $value->values }}" placeholder="" autocomplete="off"></td>
                                            @break
                                            @case(2)
                                            <td><textarea class="form-control" name="name[{{ $value->name }}]" rows="3" placeholder="">{{ $value->values }}</textarea></td>
                                            @break
                                            @case(3)
                                            <td>
                                                @foreach (explode(',',$value->value) as $keyTwo=>$valueTwo)
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" name="name[{{ $value->name }}]" value="{{ $valueTwo }}" @if ($value->values == $valueTwo) checked @endif>
                                                            {{ $valueTwo }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </td>
                                            @break
                                            @case(4)
                                            <td>
                                                @foreach (explode(',',$value->value) as $keyTwo=>$valueTwo)
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" name="name[{{ $value->name }}][]" value="{{ $valueTwo }}" @if (in_array($valueTwo,explode(',',$value->values))) checked @endif>
                                                            {{ $valueTwo }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </td>
                                            @break
                                            @case(5)
                                            <td>
                                                <select name="name[{{ $value->name }}]" class="form-control select2" style="width: 100%;">
                                                @foreach (explode(',',$value->value) as $keyTwo=>$valueTwo)
                                                        <option value="{{ $valueTwo }}" @if (in_array($valueTwo,explode(',',$value->values))) selected @endif>{{ $valueTwo }}</option>
                                                @endforeach
                                                </select>
                                            </td>
                                            @break
                                            @default
                                            <td>{{ $value->type }}</td>
                                        @endswitch
                                    </tr>
                                @endforeach
                                <tr>
                                    {{ csrf_field() }}
                                    <td colspan="2"><input type="submit" value="修改"></td>
                                </tr>
                            </form>
                        @else
                            <tr>
                                <td colspan="2" align="center">暂无数据</td>
                            </tr>
                        @endif
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix text-center">
                </div>
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col-md-12 -->
    </div>
    <!-- /.row -->
</section>

<!-- jQuery 3 -->
<script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- toastr -->
<script src="{{ asset('bower_components/toastr/build/toastr.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('bower_components/select2/dist/js/select2.full.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<!-- function warning -->
<script type="text/javascript">
    function warning(info, url){
        if(window.confirm(info)){
            window.location.href = url;
        }
    }
    toastr.options = {closeButton: true,progressBar: true};
    $(function () {
        $('.select2').select2();
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
