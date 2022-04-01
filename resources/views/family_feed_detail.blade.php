@extends('layouts.page')
@php $title = 'Family feed'; @endphp
@section('title', @$title)
@section('pageContent')

<div class="post-detail-page">
            <div class="inner_back_center_row row">
                <div class="inner_back_col">
                    <a href="family_feed.php" class="btn_back">
                        <img src="images/icons/back_btn.svg" class="img-fluid" alt="VioGraf" />
                    </a>
                </div>
                <div class="inner_center_page">
                    <div class="sectionbox">
                        <div class="post-img-area">
                            <div class="post-imgbox">
                                <img src="images/detail_pages/family_feed_detail.jpg" class="img-fluid" alt="Viograf" />
                            </div>
                            <div class="post-type-user">
                                <div class="post-type">
                                    <a class="btn_web yellow">Experience</a>
                                </div>
                                <div class="post-user">
                                    <span>
                                        <img src="images/userdefault-1.svg" alt="Family Feed" class="img-fluid">
                                    </span>
                                    <p>Nancy </p>
                                </div>
                            </div>
                        </div>
                        <div class="post-detail-area">
                            <div class="post_title_row">
                                <div class="post_title_box">
                                    <h1 class="main_title">Visit to Leh Ladakh
                                        <i>3 mins ago</i>
                                    </h1>
                                </div>
                            </div>
                            <div class="post_detail_box">
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                    Ipsum has
                                    been the industry's standard dummy text ever since the 1500s, when an unknown
                                    printer took
                                    a galley of type and scrambled it to make a type specimen book. It has survived not
                                    only
                                    five centuries, but also the leap into electronic typesetting, remaining essentially
                                    unchanged.
                                    It was popularised in the 1960s with the release of Letraset sheets containing Lorem
                                    Ipsum passages,
                                    and more recently with desktop publishing software like Aldus PageMaker including
                                    versions of
                                    Lorem Ipsum.</p>
                                <p>There are many variations of passages of Lorem Ipsum available, but the majority have
                                    suffered
                                    alteration in some form, by injected humour, or randomised words which don't look
                                    even slightly
                                    believable. If you are going to use a passage of Lorem Ipsum, you need to be sure
                                    there isn't
                                    anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators
                                    on the
                                    Internet tend to repeat predefined chunks as necessary, making this the first true
                                    generator
                                    on the Internet.</p>
                                <p>It uses a dictionary of over 200 Latin words, combined with a handful of model
                                    sentence structures,
                                    to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is
                                    therefore always free
                                    from repetition, injected humour, or non-characteristic words etc.</p>
                            </div>
                            <div>
                                <ul class="like_comments_row">
                                    <li class="like_box">
                                        <button class="active"><i class="fa fa-heart" aria-hidden="true"></i>28
                                            Likes</button>
                                    </li>
                                    <li class="like_comments">
                                        <button><i class="fa fa-comments" aria-hidden="true"></i>15 Comments</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="comment_area">
                                <div class="comment_text_box">
                                    <div class="img_col">
                                        <img src="images/userdefault-1.svg" alt="Family Feed" class="img-fluid">
                                    </div>
                                    <div class="form-group form_box"> 
                                        <input type="text" name="txtfname" placeholder="Write a comment..." minlength="2" class="form-control required">
                                    </div>
                                 </div>
                                 <ul class="comment_list">
                                    <li>
                                        <div class="img_col">
                                            <img src="images/userdefault-1.svg" alt="Family Feed" class="img-fluid">
                                        </div>
                                        <div class="commentdetail">
                                            <h4>Bob </h4>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna
                                                aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco.</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="img_col">
                                            <img src="images/userdefault-1.svg" alt="Family Feed" class="img-fluid">
                                        </div>
                                        <div class="commentdetail">
                                            <h4>Jessica </h4>
                                            <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque.</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="img_col">
                                            <img src="images/userdefault-1.svg" alt="Family Feed" class="img-fluid">
                                        </div>
                                        <div class="commentdetail">
                                            <h4>Robert  </h4>
                                            <p>No one rejects, dislikes, or avoids pleasure itself, because it is pleasure.</p>
                                        </div>
                                    </li>
                                </ul>
                                <div class="view_more_commentsarea">
                                    <button>View more comments</button>
                                    <span>3 of 15</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @stop
