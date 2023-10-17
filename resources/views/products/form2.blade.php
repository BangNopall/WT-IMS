<div class="modal fade" id="modal-form" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog"><!-- Log on to codeastro.com for more projects! -->
        <div class="modal-content">
            <form id="form-item" method="POST" class="form-horizontal" data-toggle="validator"
                enctype="multipart/form-data">
                @csrf
                {{ method_field('POST') }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title"></h3>
                </div>


                <div class="modal-body">
                    <input type="hidden" id="id" name="id">


                    <div class="box-body">
                        <div class="form-group">
                            <label>nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" autofocus required>
                            <span class="help-block with-errors"></span>
                        </div>

                        <div class="form-group">
                            <label>Foto</label>
                            <input type="file" class="form-control" id="image" name="image">
                            <span class="help-block with-errors"></span>
                        </div>

                        <div class="form-group">
                            <label>Kategori</label>
                            {!! Form::select('category_id', $category, null, [
                                'class' => 'form-control select',
                                'placeholder' => '-- Pilih kategori --',
                                'id' => 'category_id',
                                'required',
                            ]) !!}
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <!-- /.box-body -->

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>

            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div><!-- Log on to codeastro.com for more projects! -->
<!-- /.modal -->
