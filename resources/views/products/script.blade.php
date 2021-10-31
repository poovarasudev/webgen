@section('script')
    <script type="text/javascript">
        var dataTable = $('.products-datatable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 10,
            scrollX: true,
            order: [[ 4, "desc" ]],
            columnDefs: [
                {'width': '20%', 'targets': 0},
                {'width': '15%', 'targets': 1},
                {'width': '10%', 'targets': 2},
                {'width': '40%', 'targets': 3},
                {'width': '10%', 'targets': 4},
                {'width': '10%', 'targets': 5},
            ],
            ajax: '{{ route('get-products') }}',
            columns: [
                {data: 'name', name: 'name', orderable:false},
                {data: 'user.name', name: 'user.name', orderable:false},
                {data: 'price', name: 'price'},
                {data: 'description', name: 'description', orderable:false},
                {data: 'created_at', name: 'created_at', type: 'dd-mmm-yyyy'},
                {data: 'action', name: 'action', orderable:false, serachable:false},
            ]
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#submit-create-product-form').click(function(e) {
            resetDangerOrSuccessAlert();
            if (validateForm('create-product-form')) {
                $.ajax({
                    url: "{{ route('products.store') }}",
                    method: "POST",
                    data: $('#create-product-form').serialize(),
                    success: function(result) {
                        showSuccess("Product created successfully.");
                        setTimeout(function () {
                            swal.close();
                            $('#create-product-modal').modal('hide');
                            dataTable.ajax.reload();
                        }, 1000);
                    },
                    error: function (error) {
                        if(error.status === 422) {
                            showValidationErrors(error);
                            $('.alert-danger').show();
                        } else {
                            showError("Error while creating product.");
                        }
                    }
                });
            }
        });

        $('#create-product-modal').on('shown.bs.modal', function (e) {
            resetAll();
        })

        var currentIdForEdit = '';
        function editProduct(id) {
            resetDangerOrSuccessAlert();
            var productDetail = getProductFromDataTable(id);
            $('#name-edit').val(productDetail['name']);
            $('#price-edit').val(productDetail['price'].replaceAll(',', ''));
            $('#description-edit').val(productDetail['description']);
            currentIdForEdit = id;
            $('#edit-product-modal').show();
        }

        $('#submit-edit-product-form').click(function(e) {
            resetDangerOrSuccessAlert();
            if (validateForm('edit-product-form')) {
                $.ajax({
                    url: "products/" + currentIdForEdit,
                    method: 'PUT',
                    data: $('#edit-product-form').serialize(),
                    success: function(result) {
                        showSuccess("Product edited successfully.");
                        setTimeout(function () {
                            swal.close();
                            $('#edit-product-modal').hide();
                            dataTable.ajax.reload();
                        }, 1000);
                    },
                    error: function (error) {
                        if(error.status === 422) {
                            showValidationErrors(error);
                        } else {
                            showError("Error while updating product.");
                        }
                    }
                });
            }
        });

        $('.edit-model-close').on('click', function(){
            $('#edit-product-modal').hide();
        });

        function deleteProduct(id) {
            var productName = getProductFromDataTable(id)['name'];
            swal({
                title: "Are you sure to delete Product - '" + productName + "'?",
                text: "Once deleted, you will not be able to recover it",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: "products/" + id,
                            method: 'DELETE',
                            success: function (response) {
                                showSuccess("\'" + productName + "\' deleted successfully.");
                                setTimeout(function () {
                                    swal.close();
                                    $('#delete-product-modal').modal('hide');
                                    dataTable.ajax.reload();
                                }, 1000);
                            },
                            error: function (error) {
                                showError("Error while deleting product.");
                            }
                        });
                    }
                });
        }

        function setTwoNumberDecimal(el) {
            el.value = parseFloat(el.value).toFixed(2);
        }

        function validateForm(formId) {
            var form = $('#' + formId);
            form.valid();
            return form[0].checkValidity();
        }

        function resetAll() {
            $("body").find('form').find('input,  textarea').val('');
            resetDangerOrSuccessAlert();
        }

        function resetDangerOrSuccessAlert() {
            $('.alert-danger').html('');
            $('.alert-danger').hide();
        }

        function showValidationErrors(error) {
            $.each($.parseJSON(error.responseText)['errors'], function(key, value) {
                $('.alert-danger').append('<strong><li>' + value + '</li></strong>');
            });
            $('.alert-danger').show();
        }

        function getProductFromDataTable(id) {
            return dataTable.row("#" + id).data();
        }

        function showError(msg) {
            swal({
                title: "Warning!",
                text: msg,
                icon: "warning",
            });
        }

        function showSuccess(msg) {
            swal({
                title: msg,
                text: " ",
                icon: "success",
                buttons: false,
            });
        }
    </script>
@endsection
