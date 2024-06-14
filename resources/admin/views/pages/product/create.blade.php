@extends('admin::shared.layout')
@section('layout')
    <div class="form-admin" x-data="xService">
        <div class="form-bg"></div>
        <form id="form" class="form-wrapper" action="{!! route('admin-product-save') !!}" method="POST" enctype="multipart/form-data">
            <div class="form-header">
                <h3>
                    <i data-feather="arrow-left" s-click-link="{!! route('admin-product-list', 1) !!}"></i>
                   Create Product
                </h3>
            </div>
            {{ csrf_field() }}
            <div class="form-body">
                <div class="row-2">
                    <div class="form-row">
                        <label>Category <span>*</span> </label>
                        <select name="category_id">
                            @foreach ($category as $cat)
                                <option value="{{ $cat->id }}" {!! (request('id') && $data->category_id == $cat->id) ? 'selected' : '' !!}>  {{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <label class="error">{{ $message }}</label>
                        @enderror
                    </div>
                    <div class="form-row">
                        <label>UOM<span>*</span> </label>
                        <select name="uom_id">
                            @foreach ($uom as $u)
                                <option value="{{ $u->id }}" {!! (request('id') && $data->uom_id == $u->id) ? 'selected' : '' !!}>  {{ $u->name }}</option>
                            @endforeach
                        </select>
                        @error('uom_id')
                            <label class="error">{{ $message }}</label>
                        @enderror
                    </div>
                </div>

                <div class="row-2">
                    <div class="form-row">
                        <label>Name <span>*</span> </label>
                        <input type="text" name="name" placeholder="">
                        @error('name')
                            <label class="error">{{ $message }}</label>
                        @enderror
                    </div>
                    <div class="form-row">
                        <label>Price<span>*</span> </label>
                        <input type="number" name="price" placeholder="Enter price ...">
                        @error('price')
                            <label class="error">{{ $message }}</label>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="form-row">
                        <label>Commission % </label>
                        <input type="number" name="commission" placeholder="Enter commission ...">
                        @error('commission')
                            <label class="error">{{ $message }}</label>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="form-row">
                        <label>Image</label>
                        <div class="form-select-photo image">
                            <div class="select-photo {!! request('id') && isset($data) && $data->profile != null ? 'active' : '' !!}">
                                <div class="icon">
                                    <i data-feather="image"></i>
                                </div>
                                <div class="title">
                                    <span>Image</span>
                                </div>
                            </div>
                            <div class="image-view">
                                <img src="{!! request('id') && isset($data) && $data->image != null ? asset('file_manager' . $data->image) : null !!}"
                                    onerror="(this).src='{{ asset('images/logo/default.png') }}'" alt="">
                            </div>
                            <input type="text" name="image" s-click-fn="selectImage(event)" autocomplete="off"
                                role="presentation">
                            <input type="hidden" name="tmp_file" value="">
                        </div>
                        @error('image')
                            <label class="error">{{ $message }}</label>
                        @enderror
                    </div>
                </div>
                <div class="form-button">
                    <button type="submit" color="primary">
                        <i data-feather="save"></i>
                        <span>Submit</span>
                    </button>
                    <button color="danger" type="button" s-click-link="{!! route('admin-product-list', 1) !!}">
                        <i data-feather="x"></i>
                        <span>Cancel</span>
                    </button>
                </div>
            </div>
            <div class="form-footer"></div>
        </form>
    </div>
    @include('admin::file-manager.popup')
@stop

@section('script')
    <script lang="ts">
         $(document).ready(function() {
            $validator("#form", {
                name: {
                    required: true,
                },
                price: {
                    required: true,
                },
            });
        });
        function selectImage(e) {
            fileManager({
                multiple: false,
                afterClose: (data, basePath) => {
                    if (data?.length > 0) {
                        const parent = e.target.closest('.form-select-photo');
                        e.target.value = data[0].path;
                        parent
                            .querySelector('.select-photo')
                            .classList.add('active');
                        parent
                            .querySelector('.image-view')
                            .classList
                            .add('active');
                        parent
                            .querySelector('.image-view')
                            .childNodes[0]
                            .nextElementSibling
                            .setAttribute('src', basePath + data[0].path);
                    }
                }
            })
        }
    </script>
    <script>
        const header = {
            headers: {
                "Content-Type": "application/x-www-form-urlencoded;charset=utf-8",
                Accept: "application/json",
            },
            responseType: "json",
        };
        document.addEventListener('alpine:init', () => {
            Alpine.data("xService", () => ({}));
        });
    </script>
@stop
