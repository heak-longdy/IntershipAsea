<style>
    .bgBannerCov {
        height: 100%;
        position: absolute;
        top: 0;
        width: 100%;
        z-index: 1;
        background: black;
        opacity: 0.3;
    }

    .bannerTextGp>h3 {
        line-height: 1.5;
        font-size: 20px;
        white-space: pre-line !important;
        text-align: left;
    }

    .bannerTextGp>.h3 {
        line-height: 0;
        font-size: 30px;
    }

    .bannerTextGp>a {
        width: fit-content;
        height: 40px;
        padding: 0 15px;
        display: flex;
        align-items: center;
        color: #fff;
        text-decoration: none;
        border-radius: 0.75rem;
        grid-gap: 5px;
    }

    .bannerTextGp>a>i {
        font-size: 25px;
    }
</style>
<div class="bannerLayout" x-data="xBanner">
    <div class="bgBannerCov"></div>
    <div class="bannerGp" style="background: url({{ isset($imgUrl) ? $imgUrl : '' }});background-size: cover;">
        @if (!isset($disableText) || $disableText == '')
            <div class="bannerList">
                <div class="bannerTextGp">
                    <h3 class="fontWeight h3">{{ isset($text1) ? $text1 : '' }}</h3>
                </div>
                <div class="bannerSearchGp">
                    {{-- <div class="search">
                    <input type="text" class="search__input" placeholder="Type your text">
                    <button class="search__button">
                        <svg class="search__icon" aria-hidden="true" viewBox="0 0 24 24">
                            <g>
                                <path
                                    d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z">
                                </path>
                            </g>
                        </svg>
                    </button>
                </div> --}}
                    @if (!isset($search) || $search == '')
                        <div class="search">
                            <select name="position_id" id="position_id" x-init="fetchSelectPosition()">
                                <option value=""> Select Position</option>
                            </select>
                            <select name="sector_id" id="sector_id" x-init="fetchSelectSector()">
                                <option value=""> Select Sector</option>
                            </select>
                            <button class="search__button">
                                <svg class="search__icon" aria-hidden="true" viewBox="0 0 24 24">
                                    <g>
                                        <path
                                            d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z">
                                        </path>
                                    </g>
                                </svg>
                                <span>Search</span>
                            </button>
                        </div>
                    @endif
                </div>
                <div class="bannerTextGp">
                    <h3>{{ isset($text2) ? $text2 : '' }}</h3>
                    @if (!isset($clickhere) || $clickhere == '')
                        <a href="{{ isset($btnRoute) ? route($btnRoute) : '' }}" class="bgYellow"><i
                                class='bx bxs-hand-right'></i><span>{{ isset($btnText) ? $btnText : '' }}</span></a>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data("xBanner", () => ({
            baseImageUrl: "{{ asset('file_manager') }}",
            image: "",
            init() {
                const data = @json($data ?? '');
                this.image = data?.image ?? "";
                console.log('hiiii');
            },
            fetchSelectPosition() {
                $(`#position_id`).select2({
                    placeholder: `Select Position`,
                    language: {
                        searching: () => {
                            return "Please enter a search term"; // Placeholder text for search input
                        }
                    },
                    ajax: {
                        url: '{{ route('admin-select-position') }}',
                        dataType: 'json',
                        type: "GET",
                        quietMillis: 50,
                        data: (param) => {
                            return {
                                search: param.term
                            };
                        },
                        processResults: (data) => {
                            return {
                                results: $.map(data.data, (item) => {
                                    return {
                                        text: item?.title ? item?.title : '',
                                        id: item.id
                                    }
                                })
                            };
                        }
                    }
                }).on('select2:open', (e) => {
                    $select2FocusInputSearch();
                });
            },
            fetchSelectSector() {
                $(`#sector_id`).select2({
                    placeholder: `Select Sector`,
                    language: {
                        searching: () => {
                            return "Please enter a search term"; // Placeholder text for search input
                        }
                    },
                    ajax: {
                        url: '{{ route('admin-select-position') }}',
                        dataType: 'json',
                        type: "GET",
                        quietMillis: 50,
                        data: (param) => {
                            return {
                                search: param.term
                            };
                        },
                        processResults: (data) => {
                            return {
                                results: $.map(data.data, (item) => {
                                    return {
                                        text: item?.title ? item?.title : '',
                                        id: item.id
                                    }
                                })
                            };
                        }
                    }
                }).on('select2:open', (e) => {
                    $select2FocusInputSearch();
                });
            }
        }));
    });
</script>
