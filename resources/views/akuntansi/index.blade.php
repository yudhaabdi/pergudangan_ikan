@extends('template.main')
@section('title')
    AKUNTANSI
@endsection
@section('jenis_tampilan')
    / AKUNTANSI
@endsection
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12 ml-auto mr-auto">
                <div class="card card-plain card-subcategories">
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-pills-primary nav-pills-icons justify-content-center" id="myTab" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active" id="deposit-tab" data-toggle="tab" data-target="#deposit" type="button" role="tab" aria-controls="deposit" aria-selected="true">Deposit</button>
                            </li>
                            <li>
                                <a class="nav-link" data-toggle="tab" href="#link8" role="tablist">
                                    <i class="now-ui-icons shopping_shop">Buku Besar</i>
                                </a>
                            </li>
                            <li>
                                <a class="nav-link" data-toggle="tab" href="#link9" role="tablist">
                                    <i class="now-ui-icons shopping_shop">Kas</i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="deposit" role="tabpanel" aria-labelledby="deposit-tab">
                    @extends('akuntansai.tab-deposit')
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">...</div>
                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
@endsection