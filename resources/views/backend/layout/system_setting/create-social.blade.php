@extends('backend.app')

@section('title', 'Create New Social Media')

@section('content')
    <main class="content--wrapper p-5">

        {{-- Form --}}
        <section class="container">

            <div class="card text-center backend-form-wrapper">
                <div class="card-header text-start">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="pt-3">Create New Social Media</h4>
                        <div>
                            <a href="{{ route('social.media') }}" class="btn btn-sm primary-bg">View All</a>
                        </div>
                    </div>
                </div>
                <div class="card-body my-4 text-start">

                    <form action="{{ route('social.media.store') }}" method="POST" class="mb-4">
                        @csrf
                        <div class="row">

                            <div class="col-md-6 col-sm-12">
                                <label for="platform" class="form-label text-light">Social Media</label>
                                <div class="form-outline">
                                    <select name="platform" class="form-select text-light" id="platform">
                                        <option value="facebook">Facebook</option>
                                        <option value="tiktok">TikTok</option>
                                        <option value="twitter">Twitter</option>
                                        <option value="instagram">Instagram</option>
                                        <option value="youtube">YouTube</option>
                                        <option value="linkedin">LinkedIn</option>
                                        <option value="discord">Discord</option>
                                        <option value="telegram">Telegram</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-6">
                                <label for="link" class="form-label text-light">Link: </label>
                                <div class="form-outline">
                                    <input type="text" name="link" class="form-control  text-light" id="link"
                                        value="" />
                                </div>
                            </div>

                            <div class="col-6">
                                <label for="status" class="form-label text-light">Status</label>
                                <div class="form-outline">
                                    <select name="status" id="status" class="form-select text-light">
                                        <option value="active">Active</option>
                                        <option value="deactive">Deactive</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="d-flex justify-content-end gap-3 mt-5">
                            <button type="submit" class="btn btn-danger text-white px-4">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>

@endsection
