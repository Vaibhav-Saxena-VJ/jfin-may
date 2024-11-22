@extends('layouts.header')
@section('title')
    @parent
    JFS | Details Property
@endsection
@section('content')
@parent
<!-- Breadcrumbs -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('partnerDashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('allProperties') }}">List of Property</a></li> 
        <li class="breadcrumb-item active" aria-current="page">Property Details</li>
    </ol>
</nav>
 
<!-- Begin Page Content -->
<div class="container-fluid">
    <?php 
    
        foreach($data['propertie_details'] as $v) {  

            $price_range = $v->from_price. " to ". $v->to_price;
            $img = env('baseURL'). "/".$v->image;
            $boucher = env('baseURL'). "/".$v->boucher;
           
    ?>
      
        <div class="container-fluid">
            
                <!-- Title -->
                <div class="d-flex justify-content-between align-items-lg-center py-3 flex-column flex-lg-row">
                    <h2 class="h5 mb-3 mb-lg-0"><a href="../../pages/admin/customers.html" class="text-muted"><i class="bi bi-arrow-left-square me-2"></i></a> Property Details</h2>

                 <?php
                        if($v->is_active == 0){ ?>

                    <div class="hstack gap-3">
                        <button class="btn btn-light btn-sm btn-icon-text"><i class="bi bi-x"></i> <span class="text">Cancel</span></button>
                        <button type="submit" class="btn btn-primary btn-sm btn-icon-text" onclick="activateProperty('{{$v->properties_id}}')"><i class="bi bi-save"></i> <span class="text">Activate</span></button> 
                    </div>

                   <?php } ?> 
                </div>


                <input type="hidden" name="creator_id" value=" {{ Session::get('user_id') }}" />
               

                <!-- Main content -->
                <div class="row">
                    <!-- Left side -->
                    <div class="col-lg-8">
                        <!-- Basic information -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <h3 class="h6 mb-4">Basic information</h3>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Type Property</label>
                                            <input type="text"  class="form-control" placeholder="Property Title" value="{{ $v->category_name }}" readonly />
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Property Title</label>
                                            <input type="text" name="property_title" class="form-control" placeholder="Property Title" value="{{ $v->title }}" readonly />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Builder Name</label>
                                            <input type="text" name="builder_name" class="form-control" placeholder="Builder Name" readonly value="{{ $v->builder_name }}" />
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Select BHK</label>
                                            <input type="text"  class="form-control" placeholder="Property Title" value="{{ $v->select_bhk }}" readonly />

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Address -->


                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Property Description</label>
                                    <textarea name="property_description" class="form-control" rows="2" style="resize:none" maxlength="250" value="" readonly>{{ $v->property_details }} </textarea>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Property Address</label>
                                    <textarea name="property_address" class="form-control" rows="2" style="resize:none" maxlength="250" value="" readonly>{{ $v->address }}</textarea>
                                </div>
                            </div>
                        </div>

                       
                        
                        <div class="card mb-4" style="padding:3%">
                            <div class="row">
                            
                                    <div class="col-lg-6">
                                        <label class="form-label">Email ID</label>
                                        <input type="email" class="form-control jixlink2" name="email_id" value="{{ $v->email }}" readonly>
                                       
                                    </div>
                                
                                
                                    <div class="col-lg-6">
                                        <label class="form-label">Contact Number</label>
                                        <input type="tel" class="form-control jixlink2" name="contact_number" value="{{ $v->contact }}" readonly>
                                       
                                    </div>
                              
                               
                            </div>
                        </div>

                    </div>
                    <!-- Right side -->
                    <div class="col-lg-4">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h3 class="h6">Property Image</h3>
                                <img src="{{ $img }}" class="img-thumbnail" />

                                <h3 class="h6 mt-2">Property Boucher</h3>
                                <a href = "{{ $boucher }}">Boucher URL </a>

                            </div>
                        </div>
                        <!-- Notes -->

                        <div class="card mb-4">
                            <div class="card-body">
                                <label class="form-label">Price Range</label>
                                <input type="text" class="form-control jixlink2" value="{{ $price_range }}" readonly>
                            </div>    
                        </div>

                        <div class="card mb-4">
                            <div class="card-body">
                            <h3 class="h6">Amenities Description</h3>
                            <textarea class="form-control" rows="6" style="resize:none" name="amenities" readonly > {{ $v->facilities }}</textarea>
                            </div>
                        </div>
                       
                      

                    </div>
                </div>
            </div>
  
   <?php } ?>
</div>            

            
@endsection
@section('script')
@parent

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> 
<script>   
    function activateProperty(id)
	{
		$.ajax({
            url:"{{Route('activate')}}", 
            type: 'post',
            dataType: 'json',
            data: {
                'propertie_id': id,               
                '_token': '{{ csrf_token() }}',
                },
            success: function (response) {
                // console.log(response);
                if(response.status == 0){
                    swal({
                        title: response.error,
                        text: "",
                        type: "success",
                        icon: "success",
                        showConfirmButton: true
                    }).then(function(){ 
                        location.reload();
                    });
                }else{
                    swal({
                        title: response.msg,
                        text: "",
                        type: "success",
                        icon: "success",
                        showConfirmButton: true
                    }).then(function(){ 
                        window.location.href = "/partner/pendingProperties";
                    });
                }                           
            }
        });      
	}

</script>

@endsection
