@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            <b>{{ __('Products') }}</b>
                            <button style="float: right; font-weight: 900;" class="btn btn-info btn-sm" type="button" data-toggle="modal" data-target="#create-product-modal">
                                Create Product
                            </button>
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-bordered products-datatable">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Created By</th>
                                    <th>Price</th>
                                    <th>Description</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal" id="create-product-modal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form id="create-product-form">
                            <div class="modal-header">
                                <h4 class="modal-title">Create Product</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="display: none;">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="form-group">
                                    <label for="name">Name:</label>
                                    <input type="text" class="form-control" name="name" id="name" required minlength="2" maxlength="50">
                                </div>
                                <div class="form-group">
                                    <label for="title">Price:</label>
                                    <input type="number" step="0.01" class="form-control" name="price" id="price" min="1" max="10000000" required
                                           onchange="setTwoNumberDecimal(this)">
                                </div>
                                <div class="form-group">
                                    <label for="description">Description:</label>
                                    <textarea class="form-control" name="description" id="description" required></textarea>
                                </div>
                            </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-success" id="submit-create-product-form">Save</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal" id="edit-product-modal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form id="edit-product-form">
                            <div class="modal-header">
                                <h4 class="modal-title">Products Edit</h4>
                                <button type="button" class="close edit-model-close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="display: none;">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="form-group">
                                    <label for="name">Name:</label>
                                    <input type="text" class="form-control" name="name" id="name-edit" required minlength="2" maxlength="50">
                                </div>
                                <div class="form-group">
                                    <label for="title">Price:</label>
                                    <input type="number" step="0.01" class="form-control" name="price" id="price-edit" min="1" max="10000000" required
                                           onchange="setTwoNumberDecimal(this)">
                                </div>
                                <div class="form-group">
                                    <label for="description">Description:</label>
                                    <textarea class="form-control" name="description" id="description-edit" required></textarea>
                                </div>
                            </div>
                        </form>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger edit-model-close" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-success" id="submit-edit-product-form">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@include('products.script')
