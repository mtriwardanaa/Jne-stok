@extends('master')

@section('title', 'Dashboard')
@section('dashboard', 'open')
@section('dashboard-menu', 'active')

@section('head-title', 'Dashboard')
@section('head-sub-title', 'Application')

@section('content')
	@include('partial.notification')
	<div class="row invisible" data-toggle="appear">
        <div class="col-6 col-xl-3">
            <a class="block block-link-pop text-right bg-primary" href="javascript:void(0)">
                <div class="block-content block-content-full clearfix border-black-op-b border-3x">
                    <div class="float-left mt-10 d-none d-sm-block">
                        <i class="si si-bar-chart fa-3x text-primary-light"></i>
                    </div>
                    <div class="font-size-h3 font-w600 text-white" data-toggle="countTo" data-speed="1000" data-to="8900">0</div>
                    <div class="font-size-sm font-w600 text-uppercase text-white-op">Sales</div>
                </div>
            </a>
        </div>
        <div class="col-6 col-xl-3">
            <a class="block block-link-pop text-right bg-earth" href="javascript:void(0)">
                <div class="block-content block-content-full clearfix border-black-op-b border-3x">
                    <div class="float-left mt-10 d-none d-sm-block">
                        <i class="si si-trophy fa-3x text-earth-light"></i>
                    </div>
                    <div class="font-size-h3 font-w600 text-white">$<span data-toggle="countTo" data-speed="1000" data-to="2600">0</span></div>
                    <div class="font-size-sm font-w600 text-uppercase text-white-op">Earnings</div>
                </div>
            </a>
        </div>
        <div class="col-6 col-xl-3">
            <a class="block block-link-pop text-right bg-elegance" href="javascript:void(0)">
                <div class="block-content block-content-full clearfix border-black-op-b border-3x">
                    <div class="float-left mt-10 d-none d-sm-block">
                        <i class="si si-envelope-letter fa-3x text-elegance-light"></i>
                    </div>
                    <div class="font-size-h3 font-w600 text-white" data-toggle="countTo" data-speed="1000" data-to="260">0</div>
                    <div class="font-size-sm font-w600 text-uppercase text-white-op">Messages</div>
                </div>
            </a>
        </div>
        <div class="col-6 col-xl-3">
            <a class="block block-link-pop text-right bg-corporate" href="javascript:void(0)">
                <div class="block-content block-content-full clearfix border-black-op-b border-3x">
                    <div class="float-left mt-10 d-none d-sm-block">
                        <i class="si si-fire fa-3x text-corporate-light"></i>
                    </div>
                    <div class="font-size-h3 font-w600 text-white" data-toggle="countTo" data-speed="1000" data-to="4252">0</div>
                    <div class="font-size-sm font-w600 text-uppercase text-white-op">Online</div>
                </div>
            </a>
        </div>
        <!-- END Row #1 -->
    </div>
    <div class="row gutters-tiny invisible" data-toggle="appear">
        <!-- Row #1 -->
        <div class="col-6 col-md-4 col-xl-2">
            <a class="block text-center" href="javascript:void(0)">
                <div class="block-content ribbon ribbon-bookmark ribbon-crystal ribbon-left bg-gd-dusk">
                    <div class="ribbon-box">750</div>
                    <p class="mt-5">
                        <i class="si si-book-open fa-3x text-white-op"></i>
                    </p>
                    <p class="font-w600 text-white">Articles</p>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <a class="block text-center" href="javascript:void(0)">
                <div class="block-content bg-gd-primary">
                    <p class="mt-5">
                        <i class="si si-plus fa-3x text-white-op"></i>
                    </p>
                    <p class="font-w600 text-white">New Article</p>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <a class="block text-center" href="be_pages_forum_categories.html">
                <div class="block-content ribbon ribbon-bookmark ribbon-crystal ribbon-left bg-gd-sea">
                    <div class="ribbon-box">16</div>
                    <p class="mt-5">
                        <i class="si si-bubbles fa-3x text-white-op"></i>
                    </p>
                    <p class="font-w600 text-white">Comments</p>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <a class="block text-center" href="be_pages_generic_search.html">
                <div class="block-content bg-gd-lake">
                    <p class="mt-5">
                        <i class="si si-magnifier fa-3x text-white-op"></i>
                    </p>
                    <p class="font-w600 text-white">Search</p>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <a class="block text-center" href="be_comp_charts.html">
                <div class="block-content bg-gd-emerald">
                    <p class="mt-5">
                        <i class="si si-bar-chart fa-3x text-white-op"></i>
                    </p>
                    <p class="font-w600 text-white">Statistics</p>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <a class="block text-center" href="javascript:void(0)">
                <div class="block-content bg-gd-corporate">
                    <p class="mt-5">
                        <i class="si si-settings fa-3x text-white-op"></i>
                    </p>
                    <p class="font-w600 text-white">Settings</p>
                </div>
            </a>
        </div>
        <!-- END Row #1 -->
    </div>
    <div class="row gutters-tiny invisible" data-toggle="appear">
        <!-- Row #4 -->
        <div class="col-md-4">
            <div class="block block-transparent bg-primary">
                <div class="block-content block-content-full">
                    <div class="py-20 text-center">
                        <div class="mb-20">
                            <i class="fa fa-envelope-open fa-4x text-primary-light"></i>
                        </div>
                        <div class="font-size-h4 font-w600 text-white">19.5k Subscribers</div>
                        <div class="text-white-op">Your main list is growing!</div>
                        <div class="pt-20">
                            <a class="btn btn-rounded btn-alt-primary" href="javascript:void(0)">
                                <i class="fa fa-cog mr-5"></i> Manage list
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="block block-transparent bg-info">
                <div class="block-content block-content-full">
                    <div class="py-20 text-center">
                        <div class="mb-20">
                            <i class="fa fa-twitter fa-4x text-info-light"></i>
                        </div>
                        <div class="font-size-h4 font-w600 text-white">+98 followers</div>
                        <div class="text-white-op">You are doing great!</div>
                        <div class="pt-20">
                            <a class="btn btn-rounded btn-alt-info" href="javascript:void(0)">
                                <i class="fa fa-users mr-5"></i> Check them out
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="block block-transparent bg-success">
                <div class="block-content block-content-full">
                    <div class="py-20 text-center">
                        <div class="mb-20">
                            <i class="fa fa-check fa-4x text-success-light"></i>
                        </div>
                        <div class="font-size-h4 font-w600 text-white">Personal Plan</div>
                        <div class=" text-white-op">This is your current active plan</div>
                        <div class="pt-20">
                            <a class="btn btn-rounded btn-alt-success" href="javascript:void(0)">
                                <i class="fa fa-arrow-up mr-5"></i> Upgrade to VIP
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Row #4 -->
    </div>
@endsection

@section('script')
	<script src="assets/js/pages/be_pages_dashboard.min.js"></script>
@endsection