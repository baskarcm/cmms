<!--Confirm deleted modal-->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header no-bd">
                <h2 class="modal-title">
                    Delete Confirmation
                </h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this?</p>
            </div>
            <div class="modal-footer no-bd">
                <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" type="button" id="confirmDeleted" data-loading-text="Checking.." data-loading="" data-text="" >Confirm</button>
            </div>
        </div>
    </div>
</div>

@push("include_js")
<script type="text/javascript">
    $(document).ready(function() {
       

        //DELETE Action 
        $("table").on("click", ".delete-data",function(e){
            e.preventDefault();
               var dataId = $(this).data("id");
               if(!isNaN(dataId) && dataId > 0){
                    $("#deleteConfirmModal").modal({ backdrop: 'static', keyboard: false }).off('click', '#confirmDeleted').on('click', '#confirmDeleted', function (e) {
                        loadButton('#confirmDeleted');
                        $.ajax({
                           type: "POST",
                           url: delete_url,
                           data: {data: dataId},
                           dataType: "json",
                           success: function(data) {
                                loadButton('#confirmDeleted');
                                $("#deleteConfirmModal").modal('hide');
                                if(data.success == 1){
                                    notifySuccess(data.message);
                                    table.fnDraw();
                                }
                                else
                                {
                                    notifyError(data.message);
                                }
                           }
                        }); 
                   });
                }
               
           });
    });
</script>
@endpush