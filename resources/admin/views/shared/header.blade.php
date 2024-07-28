@section('title')
    | {{ $header_name }}
@stop
<style>
    /* .search-label {
        display: flex;
        align-items: center;
        box-sizing: border-box;
        position: relative;
        border: 1px solid transparent;
        border-radius: 12px;
        overflow: hidden;
        background: #3D3D3D;
        padding: 9px;
        cursor: text;
        height: 35px;
        font-size: 14px;
        margin-right: 15px;
    }

    .search-label:hover {
        border-color: gray;
    }

    .search-label:focus-within {
        background: #464646;
        border-color: gray;
    }

    .search-label input {
        outline: none;
        width: 100%;
        border: none;
        background: none;
        color: rgb(162, 162, 162);
    }

    .search-label input:focus+.slash-icon,
    .search-label input:valid+.slash-icon {
        display: none;
    }

    .search-label input:valid~.search-icon {
        display: block;
    }

    .search-label input:valid {
        width: calc(100% - 22px);
        transform: translateX(20px);
    }

    .search-label svg,
    .slash-icon {
        position: absolute;
        color: #7e7e7e;
    }

    .search-icon {
        display: none;
        width: 12px;
        height: auto;
    }

    .slash-icon {
        right: 7px;
        border: 1px solid #393838;
        background: linear-gradient(-225deg, #343434, #6d6d6d);
        border-radius: 3px;
        text-align: center;
        box-shadow: inset 0 -2px 0 0 #3f3f3f, inset 0 0 1px 1px rgb(94, 93, 93), 0 1px 2px 1px rgba(28, 28, 29, 0.4);
        cursor: pointer;
        font-size: 12px;
        width: 15px;
    }

    .slash-icon:active {
        box-shadow: inset 0 1px 0 0 #3f3f3f, inset 0 0 1px 1px rgb(94, 93, 93), 0 1px 2px 0 rgba(28, 28, 29, 0.4);
        text-shadow: 0 1px 0 #7e7e7e;
        color: transparent;
    } */
    /* .containergggg {
        position: relative;
        --size-button: 40px;
        color: white;
    }

    .input {
        padding-left: var(--size-button);
        height: var(--size-button);
        font-size: 15px;
        border: none;
        color: #fff;
        outline: none;
        width: var(--size-button);
        transition: all ease 0.3s;
        background-color: #191A1E;
        box-shadow: 1.5px 1.5px 3px #0e0e0e, -1.5px -1.5px 3px rgb(95 94 94 / 25%), inset 0px 0px 0px #0e0e0e, inset 0px -0px 0px #5f5e5e;
        border-radius: 50px;
        cursor: pointer;
    }

    .input:focus,
    .input:not(:invalid) {
        width: 200px;
        cursor: text;
        box-shadow: 0px 0px 0px #0e0e0e, 0px 0px 0px rgb(95 94 94 / 25%), inset 1.5px 1.5px 3px #0e0e0e, inset -1.5px -1.5px 3px #5f5e5e;
    }

    .input:focus+.icon,
    .input:not(:invalid)+.icon {
        pointer-events: all;
        cursor: pointer;
    }

    .containergggg .icon {
        position: absolute;
        width: var(--size-button);
        height: var(--size-button);
        top: 0;
        left: 0;
        padding: 8px;
        pointer-events: none;
    }

    .containergggg .icon svg {
        width: 100%;
        height: 100%;
    } */
</style>
<div class="header">
    <div class="header-wrapper">
        <div class="left">
            <nav>
                <div class="navHeaderRight">{!! $header_name !!}
                    {{-- <input type="checkbox" id="switch-mode" hidden>
                    <label for="switch-mode" class="switch-mode"></label> --}}
                    {{-- <a href="#" class="notification">
                        <i class='bx bxs-bell'></i>
                        <span class="num">8</span>
                    </a> --}}
                </div>
            </nav>
        </div>
        {{-- <span class="right">
            <div class="btn-auth">
                <div class="dropdown">
                    <i data-feather="user" class="action-btn" id="dropdownMenuButton" data-mdb-toggle="dropdown"
                        aria-expanded="false">
                    </i>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li>
                            <a class="dropdown-item sign-out-btn" data-url="#">
                                <i data-feather="log-out"></i>
                                <span>Sign Out</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </span> --}}
        <div class="right">
            <nav>
                <div class="navHeaderRight">
                    {{-- <input type="checkbox" id="switch-mode" hidden>
                <label for="switch-mode" class="switch-mode"></label> --}}
                    {{-- <a href="#" class="notification">
                    <i class='bx bxs-bell'></i>
                    <span class="num">8</span>
                </a> --}}
                    {{-- search --}}
                    {{-- <label class="search-label">
                        <input type="text" name="text" class="input" required="" placeholder="Type here...">
                        <kbd class="slash-icon">/</kbd>
                        <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" version="1.1"
                            xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0"
                            viewBox="0 0 56.966 56.966" style="enable-background:new 0 0 512 512" xml:space="preserve">
                            <g>
                                <path
                                    d="M55.146 51.887 41.588 37.786A22.926 22.926 0 0 0 46.984 23c0-12.682-10.318-23-23-23s-23 10.318-23 23 10.318 23 23 23c4.761 0 9.298-1.436 13.177-4.162l13.661 14.208c.571.593 1.339.92 2.162.92.779 0 1.518-.297 2.079-.837a3.004 3.004 0 0 0 .083-4.242zM23.984 6c9.374 0 17 7.626 17 17s-7.626 17-17 17-17-7.626-17-17 7.626-17 17-17z"
                                    fill="currentColor" data-original="#000000" class=""></path>
                            </g>
                        </svg>
                    </label> --}}
                    {{-- <div class="containergggg">
                        <input type="text" name="text" class="input" required=""
                            placeholder="Type to search...">
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                                <title>Search</title>
                                <path d="M221.09 64a157.09 157.09 0 10157.09 157.09A157.1 157.1 0 00221.09 64z"
                                    fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32">
                                </path>
                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10"
                                    stroke-width="32" d="M338.29 338.29L448 448"></path>
                            </svg>
                        </div>
                    </div> --}}
                    <div class="profile" x-data="xHeader">
                        <img src="{{ asset('admin-public/logo/profile.png') }}" alt="" />
                        <ul class="profile-link">
                            <div class="profileImageTextLayout">
                                <div class="imgProfile">
                                    <img class="img" src="{{ asset('admin-public/logo/profile.png') }}"
                                        alt="" />
                                    <div class="profileText">
                                        <div class="profileName">Longdy Heak</div>
                                        <div class="profileEmail">longdyheak9999@gmail.com</div>
                                    </div>
                                </div>
                                <div class="btnProfile" @click="profileInformation">Manage You Account</div>
                            </div>
                            <div class="profileAddAccount">
                                <i class='bx bx-user-plus'></i>
                                <div>Add other account</div>
                            </div>
                            <div class="profileActionLayout" @click="signOut">
                                <i class='bx bx-log-out'></i>
                                <div>Sing Out</div>
                            </div>
                        </ul>
                    </div>

                </div>
            </nav>
        </div>
    </div>
</div>
<script>
    Alpine.data('xHeader', () => ({
        open: false,
        init() {},
        toggle() {
            this.open = !this.open
        },
        signOut() {
            console.log('wrwrwrwr');
            var queueSearch = null;
            logOut({
                title: "Select Service",
                placeholder: "@lang('global.form.filter.search')",
                onReady: (callback_data) => {
                    Axios({
                            url: `#`,
                            method: 'GET'
                        })
                        .then(response => {
                            const data = response?.data?.data.map(item => {
                                return {
                                    _id: item.id,
                                    _title: item.name?.en,
                                    _image: this.baseImageUrl + item.image,
                                    _description: '@service',
                                    ...item,
                                }
                            });
                            callback_data(data);
                        });
                },
                afterClose: (res) => {
                    if (res) {
                        // this.table.reload();
                        this.service = res;
                        this.form.service_id = res.name?.en;
                    }
                }
            });
        },
        profileInformation() {
            var queueSearch = null;
            dialogProfile({
                title: "Select Service",
                placeholder: "@lang('global.form.filter.search')",
                width: "700px",
                onReady: (callback_data) => {
                    Axios({
                            url: `#`,
                            method: 'GET'
                        })
                        .then(response => {
                            const data = response?.data?.data.map(item => {
                                return {
                                    _id: item.id,
                                    _title: item.name?.en,
                                    _image: this.baseImageUrl + item.image,
                                    _description: '@service',
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
                                url: `#`,
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
                                            _title: item.name?.en,
                                            _image: this.baseImageUrl + item
                                                .image,
                                            _description: '@service',
                                            ...item,
                                        }
                                    });
                                callback_data(data);
                            });
                    }, 1000);
                },
                afterClose: (res) => {
                    if (res) {
                        // this.table.reload();
                        this.service = res;
                        this.form.service_id = res.name?.en;
                    }
                }
            });
        }
    }));
</script>
