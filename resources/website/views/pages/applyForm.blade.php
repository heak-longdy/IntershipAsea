<style>
    .wb-header {
        color: #545454 !important;
    }

    .buttonApply {
        border: unset;
        color: var(--text-color);
        outline: none;
        border-radius: 0.75rem;
        /* padding: 1.5rem; */
        z-index: 1;
        display: flex;
        align-items: center;
        height: 55px;
        padding: 0 17px;
        grid-gap: 10px;
        background: #ff9900;
        color: #fff;
    }
    .buttonApply:disabled{
        background : #57545442 !important;
    }
</style>
@extends('website::shared.layout')
@section('layout')
    <div class="wb-home-layout">
        {{-- listJob --}}
        <div class="webContentListLayout">
            <div class="webContentList">

                <div class="applyContainer">
                    <h2>Application Form</h2>
                    <div class="applyGp">
                        <div class="appLeft">
                            <div class="inputWithLabelLy">
                                <label for="" class="inputTag">Job Title<span>*</span></label>
                                <input type="text" name="job_title" placeholder="Insert job title" class="inputField">
                                <p class="inputError">error</p>
                            </div>
                            <div class="inputWithLabelLy">
                                <label for="" class="inputTag">Full Name</label>
                                <input type="text" placeholder="Insert full name" class="inputField">
                            </div>
                            <div class="inputWithLabelLy">
                                <label for="" class="inputTag">Phone</label>
                                <input type="text" [formControl]="name" placeholder="Insert you name" class="inputField">
                            </div>
                            <div class="inputWithLabelLy">
                                <label for="" class="inputTag">Email</label>
                                <input type="text" [formControl]="name" placeholder="Insert you name" class="inputField">
                            </div>
                            {{-- <p class="remarkText">(Optional) Tell us a little bit about yourself and why you're applying for
                                this role.</p> --}}
                            <div class="inputWithLabelLy">

                                <label for="" class="inputTag">Description (Optional)</label>
                                {{-- <input type="text" [formControl]="name" placeholder="Insert you name" class="inputField"> --}}

                                <textarea _ngcontent-pyh-c10="" class="contact__form-input ng-pristine ng-valid ng-touched inputField" cols="30"
                                    id="" name="project"
                                    placeholder="Tell us a little bit about yourself and why you're applying for this role" rows="10"
                                    style="width: 400px;max-width:400px;"></textarea>
                            </div>

                            <label class="material-checkbox remarkText">
                                <input type="checkbox">
                                <span class="checkmark"></span>
                                I agree to the terms and conditions and privacy policy
                            </label>

                            <button [disabled]="loading" class="buttonApply" (click)="sendEmail($event)">
                                Send Message
                                {{-- <i class='bx bx-loader-alt bx-spin' *ngIf="loading"></i> --}}
                                <svg class="button__icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" *ngIf="!loading">
                                    <path d="M14.2199 21.9352C13.0399 21.9352 11.3699 21.1052 10.0499
                                                                                17.1352L9.32988 14.9752L7.16988 14.2552C3.20988 12.9352 2.37988 11.2652
                                                                                2.37988 10.0852C2.37988 8.91525 3.20988 7.23525 7.16988 5.90525L15.6599
                                                                                3.07525C17.7799 2.36525 19.5499 2.57525 20.6399 3.65525C21.7299 4.73525
                                                                                21.9399 6.51525 21.2299 8.63525L18.3999 17.1252C17.0699 21.1052 15.3999
                                                                                21.9352 14.2199 21.9352ZM7.63988 7.33525C4.85988 8.26525 3.86988 9.36525
                                                                                3.86988 10.0852C3.86988 10.8052 4.85988 11.9052 7.63988 12.8252L10.1599
                                                                                13.6652C10.3799 13.7352 10.5599 13.9152 10.6299 14.1352L11.4699
                                                                                16.6552C12.3899 19.4352 13.4999 20.4252 14.2199 20.4252C14.9399 20.4252
                                                                                16.0399 19.4352 16.9699 16.6552L19.7999 8.16525C20.3099 6.62525 20.2199
                                                                                5.36525 19.5699 4.71525C18.9199 4.06525 17.6599 3.98525 16.1299
                                                                                4.49525L7.63988 7.33525Z"
                                        fill="var(--container-color)">
                                    </path>
                                    <path d="M10.11 14.7052C9.92005 14.7052 9.73005 14.6352 9.58005
                                                                                14.4852C9.29005 14.1952 9.29005 13.7152 9.58005 13.4252L13.16
                                                                                9.83518C13.45 9.54518 13.93 9.54518 14.22 9.83518C14.51 10.1252 14.51
                                                                                10.6052 14.22 10.8952L10.64 14.4852C10.5 14.6352 10.3 14.7052 10.11
                                                                                14.7052Z" fill="var(--container-color)">
                                    </path>
                                </svg>
                            </button>
                        </div>
                        <div class="appRight">
                            <div class="fileUploadLayout">
                                <span class="title fontWeight">Upload a File</span>
                                <p class="message">Select a file to upload from your computer or device.</p>

                                <div class="actions">
                                    <label for="file" class="button upload-btn">
                                        <div style="display: flex;
    align-items: center;
    grid-gap: 7px;">
                                            <svg viewBox="0 0 640 512" height="1em" style="font-size: 18px;">
                                                <path
                                                    d="M144 480C64.5 480 0 415.5 0 336c0-62.8 40.2-116.2 96.2-135.9c-.1-2.7-.2-5.4-.2-8.1c0-88.4 71.6-160 160-160c59.3 0 111 32.2 138.7 80.2C409.9 102 428.3 96 448 96c53 0 96 43 96 96c0 12.2-2.3 23.8-6.4 34.6C596 238.4 640 290.1 640 352c0 70.7-57.3 128-128 128H144zm79-217c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l39-39V392c0 13.3 10.7 24 24 24s24-10.7 24-24V257.9l39 39c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-80-80c-9.4-9.4-24.6-9.4-33.9 0l-80 80z">
                                                </path>
                                            </svg>
                                            <span style="line-height: 0;
    display: flex;
    align-items: center;">Choose
                                                File</span>
                                        </div>
                                        <input hidden="" type="file" id="file">
                                    </label>
                                </div>
                                <div class="resultFile">
                                    <div class="file-uploaded">
                                        <p style="display: flex;align-items: center;    overflow: hidden;flex:1;"><i class='bx bx-file'
                                                style="    margin-right: 7px;"></i><span>profile_pic.pngprofile_pic.pngprofile_pic.pngprofile_pic.pngprofile_pic.pngprofile_pic.pngprofile_pic.png</span></p>
                                        <i class='bx bx-x' style="width: 30px;
    display: flex;
    justify-content: flex-end;"></i>
                                    </div>
                                </div>
                            </div>
                            {{-- <form class="file-upload-form">
                                <label for="file" class="file-upload-label">
                                    <div class="file-upload-design">
                                        <svg viewBox="0 0 640 512" height="1em">
                                            <path
                                                d="M144 480C64.5 480 0 415.5 0 336c0-62.8 40.2-116.2 96.2-135.9c-.1-2.7-.2-5.4-.2-8.1c0-88.4 71.6-160 160-160c59.3 0 111 32.2 138.7 80.2C409.9 102 428.3 96 448 96c53 0 96 43 96 96c0 12.2-2.3 23.8-6.4 34.6C596 238.4 640 290.1 640 352c0 70.7-57.3 128-128 128H144zm79-217c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l39-39V392c0 13.3 10.7 24 24 24s24-10.7 24-24V257.9l39 39c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-80-80c-9.4-9.4-24.6-9.4-33.9 0l-80 80z">
                                            </path>
                                        </svg>
                                        <p>Drag and Drop</p>
                                        <p>or</p>
                                        <span class="browse-button">Browse file</span>
                                    </div>
                                    <input id="file" type="file" />
                                </label>
                            </form> --}}

                            {{-- <div class="container">
                                <div class="folder">
                                    <div class="front-side">
                                        <div class="tip"></div>
                                        <div class="cover"></div>
                                    </div>
                                    <div class="back-side cover"></div>
                                </div>
                                <label class="custom-file-upload">
                                    <input class="title" type="file" />
                                    Choose a file
                                </label>
                            </div> --}}


                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@stop
@section('script')
    <script></script>
@stop
