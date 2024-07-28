<style>
    .bannerLayout {
        width: 100%;
        height: 100vh;
        background-color: #f3ecd3;
    }

    .bannerGp{
        width: 100%;
        background-size: cover;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
    }

    .bannerLayout>.bannerGp>img {
        width: 100%;
    }

    .bannerTextGp {
        padding-top: 10rem;
        text-align: center;
        color: var(--white);
    }

    /* Search */
    .search {
        display: flex;
        align-items: center;
        text-align: center;
        overflow: hidden;
        position: relative;
        width: 70%;
    }

    .search__input {
        font-size: inherit;
        background-color: #f4f2f2;
        border: none;
        color: #646464;
        padding: 0.7rem 1rem;
        border-radius: 30px;
        width: 100%;
        transition: all ease-in-out .5s;
    }

    .search__input:hover,
    .search__input:focus {
        box-shadow: 0 0 1em #00000013;
    }

    .search__input:focus {
        outline: none;
        background-color: #f0eeee;
    }

    .search__input::-webkit-input-placeholder {
        font-weight: 100;
        color: #ccc;
    }

    .search__input:focus+.search__button {
        background-color: #f0eeee;
    }

    .search__button {
        border: none;
        background-color: unset !important;
        display: flex;
        align-items: center;
        /* width: 100px; */
        padding: 0 20px;
        position: absolute;
        right: 0;
        height: 100%;
    }

    .search__button:hover {
        cursor: pointer;
    }

    .search__icon {
        height: 1.3em;
        width: 1.3em;
        fill: #b4b4b4;
    }

    /* endSearch */
</style>
<div class="bannerLayout" style="">
    <div class="bannerGp" style="background: url('../../website/img/Background-image.png');background-size: cover;">
        <div class="bannerTextGp">
            <h3>Tours & Travels</h3>
            <h1>Amazing Place on Earth</h1>
        </div>
        <div class="bannerSearchGp">
            <div class="search">
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
            </div>
        </div>
    </div>
</div>
