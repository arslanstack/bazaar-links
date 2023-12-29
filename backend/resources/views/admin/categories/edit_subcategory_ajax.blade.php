<div>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title">Edit Subcategory</h5>
    </div>
    <div class="modal-body">
        <form id="edit_sub_form" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" class="form-control" value="{{ $subcat['id'] }}">
            
            <div class="row">
                @if($subcat['image'] != '')
                <div class="col-sm-4 d-flex align-items-center">
                    <div style="width: 100px; height: 100px; overflow: hidden; margin: 0 auto;">
                        <img id="previewImage" src="{{asset('uploads/categories/' . $subcat['image'] )}}" style="width: 100%; height: 100%; object-fit: cover; display: block; margin: auto;" alt="">
                    </div>
                </div>
                @endif
                <div class="{{ $subcat['image'] ? 'col-sm-8' : 'col-sm-12' }}">
                    <div class="form-group">
                        <label><strong>Title</strong></label>
                        <input type="text" name="title" class="form-control" placeholder="Title" value="{{ $subcat['title'] }}">
                    </div>
                    <div class="form-group">
                        <label><strong>Image</strong></label>
                        <input type="file" name="image" id="subcategory_img_input" class="form-control" accept="image/*">
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="update_sub_button">Save Changes</button>
    </div>