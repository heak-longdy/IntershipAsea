@extends('admin::shared.layout')
@section('layout')
    <div class="form-admin" x-data="xStockOut">
        <div class="form-bg"></div>
        <form id="form" class="form-wrapper">
            <div class="form-header">
                <h3> <i data-feather="arrow-left" s-click-link="{!! route('admin-stock-transfer-list') !!}"></i>
                    Create New Stock Transfer
                </h3>
            </div>
            <div class="form-body">
                <template x-if="inputMutiple?.length > 0">
                    <template x-for="(item,index) in inputMutiple" :key="item.id">
                        <div class="inventoryLayoutCus stockOutLayout">
                            <div class="itemInv">
                                <div class="form-row">
                                    <label>From Shop<span>*</span></label>
                                    <input type="text" name="shop_id" placeholder="Select shop"
                                        x-model="item.shop_id.name" @click="selectShop(item.id)" readonly>
                                    <template x-if="item.shop_id.error">
                                        <label class="error" x-text="'Shop is required'"></label>
                                    </template>
                                </div>
                            </div>
                            <div class="itemInv">
                                <div class="form-row">
                                    <label>Select Product<span>*</span></label>
                                    <input type="text" name="product_id" placeholder="Select product"
                                        x-model="item.product_id.name" @click="selectProduct(item.id,item.shop_id.value)"
                                        readonly>
                                    <template x-if="item.product_id.error">
                                        <label class="error" x-text="'Product is required'"></label>
                                    </template>
                                </div>
                            </div>
                            <div class="itemInv">
                                <div class="form-row">
                                    <label>CurrentQty<span>*</span></label>
                                    <div class="boxInput" x-text="item.product_id.currentQty"
                                        x-model="item.product_id.currentQty"></div>
                                </div>
                            </div>
                            <div class="itemInv">
                                <div class="form-row">
                                    <label>Quantities<span>*</span></label>
                                    <input type="number" name="qty" value="" placeholder="Qty"
                                        x-model="item.qty.value" @input="qtyChange(item.id)">
                                    <template x-if="item.qty.error && !item.qty.currentQty">
                                        <label class="error" x-text="'Qty is required'"></label>
                                    </template>
                                    <template x-if="!item.qty.error && item.qty.currentQty">
                                        <label class="error" x-text="'Qty is limited or out of stock'"></label>
                                    </template>
                                </div>
                            </div>
                            <div class="itemInv">
                                <div class="form-row">
                                    <label>To Shop<span>*</span></label>
                                    <input type="text" name="shop_id_to" placeholder="Select shop to"
                                        x-model="item.shop_id_to.name" @click="selectShopTo(item.id)" readonly>
                                    <template x-if="item.shop_id_to.error">
                                        <label class="error" x-text="'Shop is required'"></label>
                                    </template>
                                </div>
                            </div>

                            <div class="itemInv">
                                <div class="form-row">
                                    <label>Remark</label>
                                    <input type="text" name="remark" placeholder="Remark" x-model="item.remark.value">
                                    <template x-if="item.remark.error">
                                        <label class="error" x-text="'Remark is required'"></label>
                                    </template>
                                    @error('remark')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="itemInv">
                                <div class="btnAction">
                                    <template x-if="item.remove">
                                        <button type="button" class="btn btnDelete"
                                            @click="removeInput(index,item.shop_id.value)">
                                            <span class="material-symbols-outlined">delete</span>
                                        </button>
                                    </template>
                                    <template x-if="!item.remove">
                                        <button type="button" class="btn btnAdd" @click="addInput(index)">
                                            <span class="material-symbols-outlined">add</span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>
                </template>
                <div class="form-button justContentStart from-button-cus">
                    <button type="button" color="primary" @click="subMitFrom" class="loadingBtn" :disabled="loadingSubmit">
                        <i data-feather="save" x-show="!loadingSubmit"></i>
                        <div class="loadingCus" x-show="loadingSubmit">
                            <i class="gg-spinner"></i>
                        </div>
                        <span>@lang('adminGlobal.form.button.submit')</span>
                    </button>
                    <button color="danger" :disabled="loadingSubmit" type="button" s-click-link="{!! route('admin-stock-transfer-list') !!}">
                        <i data-feather="x"></i>
                        <span>@lang('adminGlobal.form.button.cancel')</span>
                    </button>
                </div>
            </div>
            <div class="form-footer"></div>
        </form>
    </div>
@stop

@section('script')

    <script>
        $(document).ready(function() {
            $("#StartDate").datepicker({
                minDate: 0,
                dateFormat: 'dd/mm/yy',
                changeYear: true,
                changeMonth: true,
                gotoCurrent: true,
                yearRange: "-50:+0",
                onSelect: function(selected) {},
            });
        });
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
            Alpine.data("xStockOut", () => ({
                loading: false,
                loadingSubmit: false,
                memberCarData: [],
                baseImageUrl: "{{ asset('file_manager') }}",
                dataError: null,
                formData: {
                    percent: null,
                    product_id: null,
                    shop_id: null,
                    qty: null,
                    remark: null,
                },
                productIdFilter: [],
                shopIdFilter: [],
                inputMutiple: [{
                    id: Number(moment().format('YYYYMMDDHHmmss')),
                    product_id: {
                        value: "",
                        name: "",
                        error: false,
                        currentQty: "",
                    },
                    shop_id: {
                        value: "",
                        error: false,
                    },
                    shop_id_to: {
                        value: "",
                        error: false,
                    },
                    qty: {
                        value: "",
                        error: false,
                        currentQty: false,
                    },
                    remark: {
                        value: "",
                        error: false,
                    },
                    remove: false,
                }],
                init() {},
                selectShop(id) {
                    var queueSearch = 500;
                    SelectOption({
                        title: "Select shop",
                        placeholder: "@lang('global.form.filter.search')",
                        onReady: (callback_data) => {
                            Axios({
                                    url: `{{ route('admin-select-shopNotIn') }}?shop_id=${JSON.stringify(this.shopIdFilter)}`,
                                    method: 'GET'
                                })
                                .then(response => {
                                    const data = response?.data?.data.map(item => {
                                        return {
                                            _id: item.id,
                                            _title: item?.name ? item.name : "",
                                            _image: this.baseImageUrl + item
                                                .image,
                                            _description: '# ' + (item?.phone ??
                                                '---'),
                                            ...item,
                                        }
                                    });
                                    callback_data(data);
                                });
                        },
                        onSearch: (value, callback_data) => {
                            clearTimeout(queueSearch);
                            queueSearch = setTimeout(() => {
                                Axios({
                                        url: `{{ route('admin-select-shopNotIn') }}?shop_id=${JSON.stringify(this.shopIdFilter)}`,
                                        params: {
                                            search: value
                                        },
                                        method: 'GET'
                                    })
                                    .then(response => {
                                        const data = response?.data?.data.map(
                                            item => {
                                                return {
                                                    _id: item.id,
                                                    _title: item?.name ?
                                                        item.name : "",
                                                    _image: this
                                                        .baseImageUrl + item
                                                        .image,
                                                    _description: '# ' +
                                                        item?.phone ??
                                                        '---',
                                                    ...item,
                                                }
                                            });
                                        callback_data(data);
                                    });
                            }, 500);
                        },
                        afterClose: (res) => {
                            if (res) {
                                let index = this.inputMutiple.findIndex(val => val.id ===
                                    id);
                                this.inputMutiple[index].product_id.value = "";
                                this.inputMutiple[index].product_id.name = "";
                                this.inputMutiple[index].product_id.currentQty = "";
                                this.inputMutiple[index].shop_id.value = res.id;
                                this.inputMutiple[index].shop_id.name = (res?.name ??
                                    "---");
                                this.selectProductShopID(this.inputMutiple[index].shop_id
                                    .value);
                            }
                        }
                    });
                },
                selectProduct(id, shop_id) {
                    var queueSearch = 500;
                    SelectOption({
                        title: "Select product by shop",
                        placeholder: "@lang('global.form.filter.search')",
                        onReady: (callback_data) => {
                            Axios({
                                    url: '{{ route('admin-select-shop-product') }}' +
                                        `?shop_id=${(shop_id?shop_id:null)}&&product_id=${(this.productIdFilter ? JSON.stringify(this.productIdFilter):null)}`,
                                    method: 'GET'
                                })
                                .then(response => {
                                    const data = response?.data?.data.map(item => {
                                        return {
                                            _id: item.id,
                                            _title: item?.product
                                                ?.name ?? "---",
                                            _image: this
                                                .baseImageUrl + item
                                                ?.product?.image,
                                            _description: '+ ' + (
                                                item?.product
                                                ?.uom?.name ??
                                                '---'),
                                            _category: '+ ' + (item
                                                ?.product
                                                ?.category
                                                ?.name ?? '---'
                                            ),
                                            ...item,
                                        }
                                    });
                                    callback_data(data);
                                });
                        },
                        onSearch: (value, callback_data) => {
                            clearTimeout(queueSearch);
                            queueSearch = setTimeout(() => {
                                Axios({
                                        url: '{{ route('admin-select-shop-product') }}' +
                                            `?shop_id=${(shop_id?shop_id:null)}&&product_id=${(this.productIdFilter ? JSON.stringify(this.productIdFilter):null)}`,
                                        params: {
                                            search: value
                                        },
                                        method: 'GET'
                                    })
                                    .then(response => {
                                        const data = response?.data?.data.map(
                                            item => {
                                                return {
                                                    _id: item.id,
                                                    _title: item?.product
                                                        ?.name ?? "---",
                                                    _image: this
                                                        .baseImageUrl + item
                                                        ?.product?.image,
                                                    _description: '+ ' + (
                                                        item?.product
                                                        ?.uom?.name ??
                                                        '---'),
                                                    _category: '+ ' + (item
                                                        ?.product
                                                        ?.category
                                                        ?.name ?? '---'
                                                    ),
                                                    ...item,
                                                }
                                            });
                                        callback_data(data);
                                    });
                            }, 500);
                        },
                        afterClose: (res) => {
                            if (res) {
                                let index = this.inputMutiple.findIndex(val => val.id ===
                                    id);
                                this.inputMutiple[index].product_id.value = res?.product
                                    ?.id;
                                this.inputMutiple[index].product_id.name = (res?.product
                                    ?.name ?? '---');
                                this.selectProductShopID(this.inputMutiple[index].shop_id
                                    .value);
                                this.fetchData(
                                    `/admin/stock-on-hand/find/${(this.inputMutiple[index].product_id.value ?? null)}/${(this.inputMutiple[index].shop_id.value??null)}`,
                                    (data) => {
                                        this.inputMutiple[index].product_id.currentQty =
                                            data?.current_stock;
                                    });
                            }
                        }
                    });
                },
                selectShopTo(id) {
                    var queueSearch = 500;
                    SelectOption({
                        title: "Select shop",
                        placeholder: "@lang('global.form.filter.search')",
                        onReady: (callback_data) => {
                            Axios({
                                    url: `{{ route('admin-select-shopNotIn') }}?shop_id=${JSON.stringify(this.shopIdFilter)}`,
                                    method: 'GET'
                                })
                                .then(response => {
                                    const data = response?.data?.data.map(item => {
                                        return {
                                            _id: item.id,
                                            _title: item?.name ? item.name : "",
                                            _image: this.baseImageUrl + item
                                                .image,
                                            _description: '# ' + (item?.phone ??
                                                '---'),
                                            ...item,
                                        }
                                    });
                                    callback_data(data);
                                });
                        },
                        onSearch: (value, callback_data) => {
                            clearTimeout(queueSearch);
                            queueSearch = setTimeout(() => {
                                Axios({
                                        url: `{{ route('admin-select-shopNotIn') }}?shop_id=${JSON.stringify(this.shopIdFilter)}`,
                                        params: {
                                            search: value
                                        },
                                        method: 'GET'
                                    })
                                    .then(response => {
                                        const data = response?.data?.data.map(
                                            item => {
                                                return {
                                                    _id: item.id,
                                                    _title: item?.name ?
                                                        item.name : "",
                                                    _image: this
                                                        .baseImageUrl + item
                                                        .image,
                                                    _description: '# ' +
                                                        item?.phone ??
                                                        '---',
                                                    ...item,
                                                }
                                            });
                                        callback_data(data);
                                    });
                            }, 500);
                        },
                        afterClose: (res) => {
                            if (res) {
                                let index = this.inputMutiple.findIndex(val => val.id ===
                                    id);
                                this.inputMutiple[index].shop_id_to.value = res.id;
                                this.inputMutiple[index].shop_id_to.name = (res?.name ??
                                    "---");
                                this.selectProductShopID(this.inputMutiple[index].shop_id_to
                                    .value);
                            }
                        }
                    });
                },
                subMitFrom() {
                    this.checkValidation((res) => {
                        if (res.length > 0) {
                            return false;
                        } else {
                            this.btnSubmit = 'Save';
                            Swal.fire({
                                customClass: "confirm-message",
                                icon: "warning",
                                html: `Are you sure to ${this.btnSubmit} stock transfer and you can not recover back. ?`,
                                confirmButtonText: `${this.btnSubmit}`,
                                cancelButtonText: "Cancel",
                                focusConfirm: false,
                                focusCancel: true,
                            }).then(result => {
                                if (result.isConfirmed) {
                                    if (result.value == 1) {
                                        this.loadingSubmit = true;
                                        this.dataError = null;
                                        let url = `/admin/stock-transfer/save`;
                                        let fromData = JSON.stringify(this
                                        .inputMutiple);
                                        setTimeout(() => {
                                            Axios({
                                                    method: 'post',
                                                    url: url,
                                                    data: {
                                                        fromData: fromData
                                                    }
                                                })
                                                .then((response) => {
                                                    if (response?.data
                                                        ?.message ==
                                                        "success") {
                                                        window.location
                                                            .href =
                                                            '{{ route('admin-stock-transfer-list') }}';
                                                    }
                                                })
                                                .catch((error) => {
                                                    this.loadingSubmit =
                                                        false;
                                                    this.dataError = error
                                                        ?.response?.data
                                                        ?.errors;
                                                });
                                        }, 1000);
                                    }
                                }
                            });
                        }
                    });
                },
                async fetchData(url, callback) {
                    await fetch(url, {
                            method: "GET",
                            headers: {
                                "Content-Type": "application/json",
                                "Accept": "application/json",
                            }
                        })
                        .then(async (res) => {
                            let data = await res.json();
                            if (data) {
                                callback(data);
                            }
                        })
                        .catch(() => {})
                        .finally(() => {});
                },
                checkValidation(callback) {
                    let error = [];
                    if (this.inputMutiple.length > 0) {
                        this.inputMutiple.forEach(val => {
                            if (!val.product_id.value) {
                                val.product_id.error = true;
                                error.push(val.name);
                            } else {
                                val.product_id.error = false;
                            }
                            if (!val.shop_id.value) {
                                val.shop_id.error = true;
                                error.push(val.shop_id.value);
                            } else {
                                val.shop_id.error = false;
                            }
                            if (!val.shop_id_to.value) {
                                val.shop_id_to.error = true;
                                error.push(val.shop_id_to.value);
                            } else {
                                val.shop_id_to.error = false;
                            }
                            if (!val.qty.value) {
                                val.qty.error = true;
                                error.push(val.qty.value);
                            } else {
                                val.qty.error = false;
                            }
                            if (val.qty.value && val.product_id?.currentQty) {
                                if (Number(val.product_id?.currentQty) < Number(val.qty
                                        .value)) {
                                    val.qty.currentQty = true;
                                    error.push(val.qty.currentQty);
                                }
                                if (Number(val.product_id?.currentQty) >= Number(val.qty
                                        .value)) {
                                    val.qty.currentQty = false;
                                }
                            }
                            if (val.qty.value && !val.product_id?.currentQty) {
                                val.qty.currentQty = true;
                                error.push(val.qty.currentQty);
                            }
                        });
                    }
                    callback(error);
                },
                addInput(index) {
                    this.checkValidation((res) => {
                        if (res.length > 0) {
                            return false;
                        } else {
                            this.inputMutiple[index].remove = true;
                            this.inputMutiple.push({
                                id: Number(moment().format('YYYYMMDDHHmmss')),
                                product_id: {
                                    value: "",
                                    error: false,
                                    currentQty: ""
                                },
                                shop_id: {
                                    value: "",
                                    error: false
                                },
                                shop_id_to: {
                                    value: "",
                                    error: false
                                },
                                qty: {
                                    value: "",
                                    error: false,
                                    currentQty: false,
                                },
                                remark: {
                                    value: "",
                                    error: false
                                },
                                remove: false
                            });
                        }
                    });
                },
                removeInput(index, shop_id) {
                    this.inputMutiple.splice(index, 1);
                    this.selectProductShopID(shop_id);
                },
                selectProductShopID(shopId = null) {
                    this.productIdFilter = [];
                    this.shopIdFilter = [];
                    this.inputMutiple.forEach(val => {
                        if (shopId == val.shop_id.value) {
                            if (val.product_id.value) {
                                this.productIdFilter.push(val.product_id.value);
                            }
                        }
                        if (val.shop_id.value) {
                            this.shopIdFilter.push(val.shop_id.value);
                        }
                        if (val.shop_id_to.value) {
                            this.shopIdFilter.push(val.shop_id_to.value);
                        }
                    });
                },
                resetSelect2(Item) {
                    if (Item.length > 0) {
                        Item.map(val => {
                            $("#" + val).val('');
                            $("#" + val).select2();
                        });
                    }
                },
                qtyChange(id) {
                    let index = this.inputMutiple.findIndex(val => val.id === id);
                    if (this.inputMutiple[index].qty.value && this.inputMutiple[index].product_id
                        ?.currentQty) {
                        if (Number(this.inputMutiple[index].product_id?.currentQty) < Number(this
                                .inputMutiple[index].qty.value)) {
                            this.inputMutiple[index].qty.currentQty = true;
                        } else {
                            this.inputMutiple[index].qty.currentQty = false;
                        }
                    }
                }
            }));
        });
    </script>
@stop
