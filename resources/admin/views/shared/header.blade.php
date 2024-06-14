@section('title')
    | {{ $header_name }}
@stop
<div class="header">
    <div class="header-wrapper">
        <div class="left">
            <nav>
                <div class="navHeaderRight">{!!$header_name!!}
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
        init() {
            console.log('Discuowurwourowruworuworu');
        },
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
