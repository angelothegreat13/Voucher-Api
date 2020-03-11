@extends('layout')
@section('content')
<div class="container-fluid mt-5">

    <div class="col-4 mx-auto">
        <div class="card bg-light mb-3">
            <div class="card-header">First Api Application</div>
            <div class="card-body">
                <form action="" method="">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control" name="email" id="email">
                    </div>
            
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="password">
                    </div>
                    
                    <div class="form-group">
                        <button type="button" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

@section('extra-scripts')
<script type="text/javascript">

console.log("Hello world");

</script>
@endsection

