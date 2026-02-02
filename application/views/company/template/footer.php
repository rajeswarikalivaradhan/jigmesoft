<div id="id-ace-settings-modal"
     class="my-1 my-lg-2 modal modal-nb ace-aside aside-right aside-offset aside-below-nav"
     data-backdrop="false" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content w-auto flex-grow-1 pb-1px radius-0 radius-l-2 border-y-2 border-l-1 brc-default-m3 bgc-white-tp1 shadow">

            <div class="modal-header p-0 radius-0 mx-3">
                <h4 class="modal-title text-primary-d1 text-140 pt-2 pl-1">Site Settings</h4>

                <button type="button" class="close m-0 mr-n2" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times text-70" aria-hidden="true"></i>
                </button>
            </div>

            <div class="modal-body mx-md-2" data-ace-scroll='{"smooth": true, "lock": true}'>
                <form autocomplete="off">
                    <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center">
                        <h5 class="text-default-d2">
                            Zoom
                        </h5>

                        <div class="btn-group btn-group-toggle align-self-end" data-toggle="buttons">
                            <label class="btn btn-sm btn-lighter-grey btn-h-light-primary btn-a-primary">
                                90%
                                <input type="radio" name="zoom-level" value="90"/>
                            </label>

                            <label class="btn btn-sm btn-lighter-grey btn-h-light-primary btn-a-primary active">
                                100%
                                <input type="radio" name="zoom-level" value="none" checked/>
                            </label>

                            <label class="btn btn-sm btn-lighter-grey btn-h-light-primary btn-a-primary">
                                110%
                                <input type="radio" name="zoom-level" value="110"/>
                            </label>

                            <label class="btn btn-sm btn-lighter-grey btn-h-light-primary btn-a-primary">
                                120%
                                <input type="radio" name="zoom-level" value="120"/>
                            </label>
                        </div>
                    </div>


                    <hr class="border-double my-md-3"/>


                    <h5 class="text-purple-d1">
                        Themes
                    </h5>

                    <div id="auto-match-div"
                         class="bgc-secondary-l4 py-1 radius-1 mb-3 border-1 radius-1 border-l-3 brc-secondary-m4">
                        <label class="mt-1 pr-2 d-flex align-items-center" for="id-auto-match">
                            <input type="checkbox" class="input-lg mx-15" id="id-auto-match" checked/>

                            <span class="pl-0 text-secondary-d1 text-90 font-bolder">
                        Match sidebar & navbar themes
                      </span>
                        </label>
                    </div>


                    <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center mt-3">
                        <h6 class="text-95 pl-1 text-grey-d1">Sidebar</h6>

                        <div class="btn-group btn-group-toggle align-self-end flex-wrap px-0  col-10 col-sm-7"
                             data-toggle="buttons">
                            <label class="btn btn-sm btn-light-default btn-text-default btn-bgc-white btn-a-default btn-h-default">
                                Dark
                                <input type="radio" name="sidebar-theme" value="dark"/>
                            </label>

                            <label class="btn btn-sm btn-light-default btn-text-default btn-bgc-white btn-a-default btn-h-default">
                                Light
                                <input type="radio" name="sidebar-theme" value="light"/>
                            </label>
                        </div>
                    </div>


                    <div>
                        <div class="d-none bgc-secondary-l1 radius-1 px-1 mb-3 mt-1 text-center"
                             id="id-sidebar-themes-dark">
                            <div class="btn-group btn-group-toggle align-self-end flex-wrap justify-content-center w-75 mx-auto align-items-center my-2 flex-equal-sm"
                                 data-toggle="buttons">
                                <label class="btn btn-xs sidebar-color border-0 sidebar-dark d-style active m-1px">
                                    <i class="fa fa-check text-white v-active"></i>
                                    <input type="radio" name="sidebar-dark" value="dark" checked/>
                                </label>

                                <label class="btn btn-xs sidebar-color border-0 sidebar-dark2 d-style m-1px">
                                    <i class="fa fa-check text-white v-active"></i>
                                    <input type="radio" name="sidebar-dark" value="dark2"/>
                                </label>

                                <label class="btn btn-xs sidebar-color border-0 sidebar-darkblue d-style m-1px">
                                    <i class="fa fa-check text-white v-active"></i>
                                    <input type="radio" name="sidebar-dark" value="darkblue"/>
                                </label>

                                <label class="btn btn-xs sidebar-color border-0 sidebar-darkslategrey d-style m-1px">
                                    <i class="fa fa-check text-white v-active"></i>
                                    <input type="radio" name="sidebar-dark" value="darkslategrey"/>
                                </label>

                                <label class="btn btn-xs sidebar-color border-0 sidebar-cadetblue d-style m-1px">
                                    <i class="fa fa-check text-white v-active"></i>
                                    <input type="radio" name="sidebar-dark" value="cadetblue"/>
                                </label>

                                <label class="btn btn-xs sidebar-color border-0 sidebar-plum d-style my-1px m-1px">
                                    <i class="fa fa-check text-white v-active"></i>
                                    <input type="radio" name="sidebar-dark" value="plum"/>
                                </label>

                                <label class="btn btn-xs sidebar-color border-0 sidebar-darkslateblue d-style m-1px">
                                    <i class="fa fa-check text-white v-active"></i>
                                    <input type="radio" name="sidebar-dark" value="darkslateblue"/>
                                </label>

                                <label class="btn btn-xs sidebar-color border-0 sidebar-purple d-style m-1px">
                                    <i class="fa fa-check text-white v-active"></i>
                                    <input type="radio" name="sidebar-dark" value="purple"/>
                                </label>

                                <label class="btn btn-xs sidebar-color border-0 sidebar-steelblue d-style m-1px">
                                    <i class="fa fa-check text-white v-active"></i>
                                    <input type="radio" name="sidebar-dark" value="steelblue"/>
                                </label>

                                <label class="btn btn-xs sidebar-color border-0 sidebar-blue d-style m-1px">
                                    <i class="fa fa-check text-white v-active"></i>
                                    <input type="radio" name="sidebar-dark" value="blue"/>
                                </label>

                                <label class="btn btn-xs sidebar-color border-0 sidebar-teal d-style m-1px d-none">
                                    <i class="fa fa-check text-white v-active"></i>
                                    <input type="radio" name="sidebar-dark" value="teal"/>
                                </label>

                                <label class="btn btn-xs sidebar-color border-0 sidebar-green d-style m-1px">
                                    <i class="fa fa-check text-white v-active"></i>
                                    <input type="radio" name="sidebar-dark" value="green"/>
                                </label>

                                <label class="btn btn-xs sidebar-color border-0 sidebar-darkcrimson d-style m-1px">
                                    <i class="fa fa-check text-white v-active"></i>
                                    <input type="radio" name="sidebar-dark" value="darkcrimson"/>
                                </label>

                                <label class="btn btn-xs sidebar-color border-0 sidebar-gradient1 d-style m-1px">
                                    <i class="fa fa-check text-white v-active"></i>
                                    <input type="radio" name="sidebar-dark" value="gradient1"/>
                                </label>

                                <label class="btn btn-xs sidebar-color border-0 sidebar-gradient2 d-style m-1px">
                                    <i class="fa fa-check text-white v-active"></i>
                                    <input type="radio" name="sidebar-dark" value="gradient2"/>
                                </label>

                                <label class="btn btn-xs sidebar-color border-0 sidebar-gradient3 d-style m-1px">
                                    <i class="fa fa-check text-white v-active"></i>
                                    <input type="radio" name="sidebar-dark" value="gradient3"/>
                                </label>

                                <label class="btn btn-xs sidebar-color border-0 sidebar-gradient4 d-style m-1px">
                                    <i class="fa fa-check text-white v-active"></i>
                                    <input type="radio" name="sidebar-dark" value="gradient4"/>
                                </label>

                                <!--
                              <label class="btn btn-xs sidebar-color border-0 sidebar-gradient5 d-style m-1px d-none">
                                <i class="fa fa-check text-white v-active"></i>
                                <input type="radio" name="sidebar-dark" value="gradient5"  />
                              </label>
                              -->

                            </div>
                        </div><!-- #id-sidebar-themes-dark -->


                        <div class="d-none" id="id-sidebar-themes-light">
                            <div class="bgc-secondary-tp2 radius-1 py-1 px-1 mb-3 mt-1 text-center">
                                <div class="d-flex btn-group btn-group-toggle align-self-end flex-wrap justify-content-center mx-auto align-items-center my-2 flex-equal-sm"
                                     data-toggle="buttons">

                                    <label class="active btn btn-xs border-0 sidebar-white2 d-style m-1px">
                                        <i class="fa fa-check text-muted v-active"></i>
                                        <input type="radio" name="sidebar-light" value="white" checked/>
                                    </label>

                                    <label class="btn btn-xs border-0 sidebar-white2 d-style m-1px d-none">
                                        <i class="fa fa-check text-muted v-active"></i>
                                        <input type="radio" name="sidebar-light" value="white2"/>
                                    </label>

                                    <label class="btn btn-xs border-0 sidebar-white3 d-style m-1px">
                                        <i class="fa fa-check text-muted v-active"></i>
                                        <input type="radio" name="sidebar-light" value="white3"/>
                                    </label>

                                    <label class="btn btn-xs border-0 sidebar-white4 d-style m-1px">
                                        <i class="fa fa-check text-muted v-active"></i>
                                        <input type="radio" name="sidebar-light" value="white4"/>
                                    </label>

                                    <label class="btn btn-xs border-0 sidebar-light d-style m-1px">
                                        <i class="fa fa-check text-muted v-active"></i>
                                        <input type="radio" name="sidebar-light" value="light"/>
                                    </label>

                                    <label class="btn btn-xs border-0 sidebar-lightblue d-style m-1px">
                                        <i class="fa fa-check text-muted v-active"></i>
                                        <input type="radio" name="sidebar-light" value="lightblue"/>
                                    </label>

                                    <label class="btn btn-xs border-0 sidebar-lightblue2 d-style m-1px">
                                        <i class="fa fa-check text-muted v-active"></i>
                                        <input type="radio" name="sidebar-light" value="lightblue2"/>
                                    </label>

                                    <label class="btn btn-xs border-0 sidebar-lightpurple d-style m-1px">
                                        <i class="fa fa-check text-muted v-active"></i>
                                        <input type="radio" name="sidebar-light" value="lightpurple"/>
                                    </label>


                                </div>
                            </div>
                        </div><!-- #id-sidebar-themes-light -->

                    </div>

                    <hr class="border-dotted"/>

                    <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center">
                        <h6 class="text-95 pl-1 text-grey-d1">Navbar</h6>

                        <div id="navbar-themes-show"
                             class="btn-group btn-group-toggle align-self-end flex-wrap px-0 col-10 col-sm-7"
                             data-toggle="buttons">
                            <label class="btn btn-sm btn-light-green btn-text-green btn-bgc-white btn-a-green btn-h-green">
                                Light
                                <input type="radio" name="navbar-theme" value="light"/>
                            </label>

                            <label class="btn btn-sm btn-light-green btn-text-green btn-bgc-white btn-a-green btn-h-green">
                                Dark
                                <input type="radio" name="navbar-theme" value="dark"/>
                            </label>
                        </div>

                        <div id="navbar-themes-show-msg"
                             class="d-none text-95 px-3 py-15 bgc-secondary-l3 border-1 brc-secondary-m4 border-dotted ml-3 radius-1">
                            Navbar themes can be viewed in<br/> <span>Dashboard <a
                                        class="btn-h-dark no-underline px-2px" href="html/dashboard.html">1</a> & <a
                                        class="btn-h-dark no-underline px-2px"
                                        href="html/dashboard-4.html">4</a></span>
                        </div>

                    </div>

                    <div>
                        <div class="d-none bgc-secondary-l1 radius-1 px-1 mb-3 mt-1 text-center"
                             id="id-navbar-themes-dark">
                            <div class="btn-group btn-group-toggle align-self-end flex-wrap justify-content-center w-75 mx-auto align-items-center my-2 flex-equal-sm"
                                 data-toggle="buttons">

                                <label class="btn btn-xs border-0 navbar-blue d-style active m-1px">
                                    <i class="fa fa-check text-white v-active"></i>
                                    <input type="radio" name="navbar-dark" value="blue" checked/>
                                </label>

                                <label class="btn btn-xs border-0 navbar-darkblue d-style m-1px">
                                    <i class="fa fa-check text-white v-active"></i>
                                    <input type="radio" name="navbar-dark" value="darkblue"/>
                                </label>

                                <label class="btn btn-xs border-0 navbar-teal d-style m-1px">
                                    <i class="fa fa-check text-white v-active"></i>
                                    <input type="radio" name="navbar-dark" value="teal"/>
                                </label>

                                <label class="btn btn-xs border-0 navbar-green d-style m-1px">
                                    <i class="fa fa-check text-white v-active"></i>
                                    <input type="radio" name="navbar-dark" value="green"/>
                                </label>

                                <label class="btn btn-xs border-0 navbar-cadetblue d-style m-1px">
                                    <i class="fa fa-check text-white v-active"></i>
                                    <input type="radio" name="navbar-dark" value="cadetblue"/>
                                </label>


                                <label class="btn btn-xs border-0 navbar-plum d-style m-1px">
                                    <i class="fa fa-check text-white v-active"></i>
                                    <input type="radio" name="navbar-dark" value="plum"/>
                                </label>

                                <label class="btn btn-xs border-0 navbar-purple d-style m-1px">
                                    <i class="fa fa-check text-white v-active"></i>
                                    <input type="radio" name="navbar-dark" value="purple"/>
                                </label>

                                <label class="btn btn-xs border-0 navbar-orange d-style m-1px">
                                    <i class="fa fa-check text-white v-active"></i>
                                    <input type="radio" name="navbar-dark" value="orange"/>
                                </label>


                                <label class="btn btn-xs border-0 navbar-brown d-style m-1px">
                                    <i class="fa fa-check text-white v-active"></i>
                                    <input type="radio" name="navbar-dark" value="brown"/>
                                </label>

                                <label class="btn btn-xs border-0 navbar-darkgreen d-style m-1px">
                                    <i class="fa fa-check text-white v-active"></i>
                                    <input type="radio" name="navbar-dark" value="darkgreen"/>
                                </label>

                                <label class="btn btn-xs border-0 navbar-skyblue d-style m-1px">
                                    <i class="fa fa-check text-white v-active"></i>
                                    <input type="radio" name="navbar-dark" value="skyblue"/>
                                </label>

                                <label class="btn btn-xs border-0 navbar-secondary d-style m-1px">
                                    <i class="fa fa-check text-white v-active"></i>
                                    <input type="radio" name="navbar-dark" value="secondary"/>
                                </label>

                            </div>
                        </div><!-- #id-navbar-themes-dark -->

                        <div class="d-none" id="id-navbar-themes-light">
                            <div class="bgc-secondary-tp2 radius-1 py-1 px-1 mb-3 mt-1 text-center">
                                <div class="btn-group btn-group-toggle align-self-end flex-wrap justify-content-center w-75 mx-auto align-items-center my-2 flex-equal-sm"
                                     data-toggle="buttons">

                                    <label class="active btn btn-xs border-0 navbar-white d-style m-1px">
                                        <i class="fa fa-check text-muted v-active"></i>
                                        <input type="radio" name="navbar-light" value="white" checked/>
                                    </label>

                                    <label class="btn btn-xs border-0 navbar-white2 d-style m-1px">
                                        <i class="fa fa-check text-muted v-active"></i>
                                        <input type="radio" name="navbar-light" value="white2"/>
                                    </label>

                                    <label class="btn btn-xs border-0 navbar-lightblue d-style m-1px">
                                        <i class="fa fa-check text-muted v-active"></i>
                                        <input type="radio" name="navbar-light" value="lightblue"/>
                                    </label>

                                    <label class="btn btn-xs border-0 navbar-lightpurple d-style m-1px">
                                        <i class="fa fa-check text-muted v-active"></i>
                                        <input type="radio" name="navbar-light" value="lightpurple"/>
                                    </label>

                                    <label class="btn btn-xs border-0 navbar-lightgreen d-style m-1px">
                                        <i class="fa fa-check text-muted v-active"></i>
                                        <input type="radio" name="navbar-light" value="lightgreen"/>
                                    </label>

                                    <label class="btn btn-xs border-0 navbar-lightgrey d-style m-1px">
                                        <i class="fa fa-check text-muted v-active"></i>
                                        <input type="radio" name="navbar-light" value="lightgrey"/>
                                    </label>

                                    <!--
                                  <label class="btn btn-xs border-0 navbar-lightyellow d-style m-1px">
                                    <i class="fa fa-check text-muted v-active"></i>
                                    <input type="radio" name="navbar-light" value="lightyellow"  />
                                  </label>

                                  <label class="btn btn-xs border-0 navbar-khaki d-style m-1px">
                                    <i class="fa fa-check text-muted v-active"></i>
                                    <input type="radio" name="navbar-light" value="khaki"  />
                                  </label>
                                  -->

                                </div>
                            </div>

                        </div><!-- #id-navbar-themes-light -->

                    </div>


                    <hr class="border-dotted"/>


                    <div class="text-95">
                        <h5 class="text-success">Layout</h5>

                        <div class="mt-3 d-flex justify-content-between align-items-center">
                            <label for="id-navbar-fixed" class="pl-1 text-grey-d1">Fixed Navbar</label>
                            <input type="checkbox" class="ace-switch" id="id-navbar-fixed" checked/>
                        </div>

                        <div class="mt-2 d-flex justify-content-between align-items-center">
                            <label for="id-sidebar-fixed" class="pl-1 text-grey-d1">Fixed Sidebar</label>
                            <input type="checkbox" class="ace-switch" id="id-sidebar-fixed" checked/>
                        </div>

                        <div class="mt-2 d-flex justify-content-between align-items-center">
                            <label for="id-footer-fixed" class="pl-1 text-grey-d1">Fixed Footer</label>
                            <input type="checkbox" class="ace-switch" id="id-footer-fixed"/>
                        </div>

                        <div class="mt-2 d-none d-xl-flex flex-column flex-md-row justify-content-md-between align-items-md-center">
                            <div class="pl-1 text-grey-d1">Boxed Layout</div>

                            <div class="w-50 btn-group btn-group-toggle flex-row flex-wrap fl1ex-md-nowrap"
                                 data-toggle="buttons">
                                <label class="btn btn-sm btn-light-primary btn-bgc-white btn-text-primary btn-h-primary btn-a-primary">
                                    None
                                    <input type="radio" name="boxed-layout" value="none"/>
                                </label>

                                <label class="btn btn-sm btn-light-primary btn-bgc-white btn-text-primary btn-h-primary btn-a-primary">
                                    All
                                    <input type="radio" name="boxed-layout" value="all"/>
                                </label>

                                <label class="btn btn-sm btn-light-primary btn-bgc-white btn-text-primary btn-h-primary btn-a-primary">
                                    Not Navbar
                                    <input type="radio" name="boxed-layout" value="not-navbar"/>
                                </label>

                                <label class="btn btn-sm btn-light-primary btn-bgc-white btn-text-primary btn-h-primary btn-a-primary active">
                                    Only Content
                                    <input type="radio" name="boxed-layout" value="only-content" checked/>
                                </label>
                            </div>
                        </div>

                        <div id="id-body-bg" class="collapse">
                            <div class="mt-3 d-none d-xl-flex flex-column flex-md-row justify-content-md-between align-items-md-center">
                                <h6 class="text-95 pl-1 text-grey-d1">Body Background:</h6>

                                <div class="btn-group btn-group-toggle align-self-end" data-toggle="buttons">
                                    <label class="btn btn-sm btn-outline-purple active  mb-1">
                                        None
                                        <input type="radio" name="body-theme" value="auto" checked/>
                                    </label>

                                    <label class="btn btn-sm btn-outline-purple mb-1">
                                        Image 1
                                        <input type="radio" name="body-theme" value="img1"/>
                                    </label>

                                    <label class="btn btn-sm btn-outline-purple mb-1">
                                        Image 2
                                        <input type="radio" name="body-theme" value="img2"/>
                                    </label>
                                </div>
                            </div>
                        </div>


                        <hr class="border-dotted my-2"/>

                        <div class="mt-1 d-flex justify-content-between align-items-center">
                            <label for="id-rtl" class="pl-1 text-grey-d1">RTL (right to left)</label>

                            <input type="checkbox" class="ace-switch" id="id-rtl"/>
                        </div>


                    </div>

                    <hr class="border-double my-md-4"/>

                    <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center">
                        <h5 class="text-info">Font</h5>

                        <div class="align-self-end w-75">
                            <select id="id-change-font"
                                    class="ace-select radius-round w-100 text-grey brc-h-info-m2">
                                <option value="lato">Lato</option>
                                <option value="manrope">Manrope</option>
                                <option value="montserrat">Montserrat</option>
                                <option value="noto-sans">Noto Sans</option>
                                <option value="open-sans" selected>Open Sans</option>
                                <option value="poppins">Poppins</option>
                                <option value="raleway">Raleway</option>
                                <option value="roboto" class="text-primary-d2 text-600">Roboto (popular)
                                </option>
                                <option value="">----</option>
                                <option value="markazi">Markazi (for RTL languages)</option>
                            </select>
                        </div>
                    </div>


                    <hr class="border-double my-md-4"/>

                    <div class="text-95">
                        <h5 class="text-orange-d2 ml-n2px">Sidebar</h5>
                        <!--
                      <div class="mt-3 d-none d-xl-flex justify-content-between align-items-center">
                          <label for="id-sidebar-compact" class="pl-1 text-grey-d2">Compact</label>

                          <div class="custom-control custom-switch d-inline-block">
                            <input type="checkbox" class="custom-control-input" id="id-sidebar-compact"  />
                            <label class="custom-control-label" for="id-sidebar-compact"></label>
                          </div>
                      </div>
                      -->

                        <div class="mt-2 d-none d-xl-flex justify-content-between align-items-center">
                            <div class="pl-1 text-grey-d1">Collapsed Mode</div>

                            <div class="btn-group btn-group-toggle flex-row" data-toggle="buttons">
                                <label class="btn btn-sm btn-outline-red active">
                                    Expand
                                    <input type="radio" name="sidebar-collapsed" value="expandable" checked/>
                                </label>

                                <label class="btn btn-sm btn-outline-red">
                                    Popup
                                    <input type="radio" name="sidebar-collapsed" value="hoverable"/>
                                </label>

                                <label class="btn btn-sm btn-outline-red">
                                    Hide
                                    <input type="radio" name="sidebar-collapsed" value="hideable"/>
                                </label>
                            </div>
                        </div>

                        <div class="mt-3 d-none d-xl-flex justify-content-between align-items-center">
                            <label for="id-sidebar-hover" class="pl-1 text-grey-d1">Submenu on Hover</label>

                            <label>
                                <input type="checkbox" class="ace-switch" id="id-sidebar-hover"/>
                            </label>
                        </div>

                        <div class="mt-2 d-flex d-xl-none justify-content-between align-items-center">
                            <label for="id-push-content" class="pl-1 text-grey-d1">Push Content</label>

                            <label>
                                <input type="checkbox" class="ace-switch" id="id-push-content"/>
                            </label>
                        </div>

                    </div>

                    <div class="my-1"></div>
                </form>
            </div>

            <div class="modal-footer d-none justify-content-center">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="fa fa-times mr-1"></i>
                    Close
                </button>
                <button type="button" class="btn btn-info">
                    <i class="fa fa-check mr-1"></i>
                    Keep changes
                </button>
            </div>

        </div><!-- .modal-content -->

        <div class="aside-header align-self-start mt-1 mt-lg-5 text-right d-style hide">
            <button type="button" class="btn btn-orange btn-lg shadow-sm pl-2 radius-l-2 f-n-hover py-1 py-md-2"
                    data-toggle="modal" data-target="#id-ace-settings-modal">
                <i class="fa fa-cog text-110 ml-1"></i>
            </button>
        </div>
    </div><!-- .modal-dialog -->
</div>
</div>
</div>
</div>
<!-- jQuery -->
<script src="<?php echo base_url(); ?>assets/js/new/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo base_url(); ?>assets/js/new/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->

<!-- include common vendor scripts used in demo pages -->
<script src="<?= base_url() ?>assets/ace/node_modules/jquery/dist/jquery.js"></script>
<script src="<?= base_url() ?>assets/ace/node_modules/popper.js/dist/umd/popper.js"></script>
<script src="<?= base_url() ?>assets/ace/node_modules/bootstrap/dist/js/bootstrap.js"></script>

<!-- include vendor scripts used in "Dashboard" page. see "/views//pages/partials/dashboard/@vendor-scripts.hbs" -->
<script src="<?= base_url() ?>assets/ace/node_modules/chart.js/dist/Chart.js"></script>

<script src="<?= base_url() ?>assets/ace/node_modules/sortablejs/Sortable.js"></script>

<!-- include ace.js -->
<script src="<?= base_url() ?>assets/ace/dist/js/ace.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue.js"></script>
<script src="<?php echo base_url(); ?>assets/js/ajax.js"></script>
<script src="<?= base_url() ?>assets/ace/app/browser/demo.js"></script>
<script src="<?= base_url() ?>assets/ace/node_modules/sweetalert2/dist/sweetalert2.all.js"></script>
<script src="<?= base_url() ?>assets/ace/node_modules/interactjs/dist/interact.js"></script>
<script>
    jQuery(function($) {

        // add some big buttons to the "Mega" menu
        $('#sidebar .nav > .nav-item.active > .submenu > .submenu-inner')
            .prepend('<li class="nav-item">\
   <div class="d-flex flex-wrap justify-content-center flex-xl-nowrap p-2 bgc-default-l4">\
    <button type="button" class="btn btn-sm btn-app btn-outline-primary btn-bgc-white radius-1 my-1 mx-1">\
			<i class="d-block h-6 fa fa-edit text-190"></i>\
			Edit\
			<span class="badge badge-warning badge-sm position-tl m-n2 text-70">11</span>\
    </button>\
    <button type="button" class="btn btn-sm btn-app btn-outline-secondary btn-bgc-white radius-1 my-1 mx-1">\
			<i class="d-block h-6 fa fa-cog text-190"></i>\
			Settings\
			<span class="badge badge-sm py-2px position-tr text-75 mt-1px text-dark-tp4">+3</span>\
		</button>\
		<button type="button" class="btn btn-sm btn-app btn-outline-success btn-bgc-white radius-1 my-1 mx-1">\
			<i class="d-block h-6 fa fa-sync text-190"></i>\
			Reload\
		</button>\
	</div>\
</li>')



        // when collapsing/expanding horizontal sidebar, remove/add `.nav-fill` class
        $('#sidebar')
            .on('collapse.ace.sidebar', function() {
                $(this).find('.nav').removeClass('nav-fill text-center')
                $('#id-full-width').prop('checked', false)
            })
            .on('expand.ace.sidebar', function() {
                $(this).find('.nav').addClass('nav-fill text-center')
                $('#id-full-width').prop('checked', true)
            })


        // make navbar non-fixed, sidebar fixed (sticky)
        $('#id-navbar-fixed').prop('checked', false)
        $('.navbar').toggleClass('navbar-fixed', false)


        /**
         $('#id-full-height')
         .on('change', function() {
      $('.sidebar .container').toggleClass('align-items-xl-end')
      $('.sidebar .nav').toggleClass('nav-link-rounded')
   })
         */

        $('#id-full-width')
            .on('change', function() {
                $('.sidebar .nav').toggleClass('nav-fill text-center')
            })

        $('#id-flip-highlight')
            .on('change', function() {
                $('.sidebar .nav').toggleClass('active-on-right')
            })

        $('#id-sm-highlight')
            .on('change', function() {
                $('.sidebar .nav').toggleClass('nav-active-sm')
            })


        ///////////////////////////////////////
        // Draw Charts

        // make sure no animation is displayed if user prefers so
        var _animate = !AceApp.Util.isReducedMotion()

        var quickStatsCanvas = document.getElementById("quickstats-chart")
        if(window.innerWidth < 500) quickStatsCanvas.height = 200

        var ctx1 = quickStatsCanvas.getContext('2d')
        var gradient1 = ctx1.createLinearGradient(0, 0, 0, quickStatsCanvas.clientHeight * 2)
        gradient1.addColorStop(0, 'rgba(23, 167, 178, 0.2)')
        gradient1.addColorStop(1, 'rgba(23, 167, 178, 0.0)')

        var gradient2 = ctx1.createLinearGradient(0, 0, 0, quickStatsCanvas.clientHeight * 1.5)
        gradient2.addColorStop(0, 'rgba(22, 176, 255, 0.2)')
        gradient2.addColorStop(1, 'rgba(22, 176, 255, 0.0)')


        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
                datasets: [{
                    type: 'line',
                    label: 'Offline',

                    data: [8000, 6000, 4000, 4500, 3000, 9000, 11000],

                    borderColor: 'rgba(23, 167, 178, 0.67)',
                    borderWidth: 1.25,

                    fill: false,
                    backgroundColor : gradient1,

                    pointRadius: 10,
                    pointBorderWidth: 10,
                    pointBackgroundColor: 'transparent',
                    pointHoverBackgroundColor: 'rgba(0, 0, 0, 0.27)',
                    pointBorderColor: 'transparent',

                    lineTension: 0.3
                },
                    {
                        type: 'line',
                        label: 'Online',

                        data: [4500, 9000, 5000, 7000, 6000, 11000, 7500],


                        borderColor: 'rgba(22, 176, 255, 0.67)',
                        borderWidth: 1.25,

                        fill: false,
                        backgroundColor : gradient2,

                        pointRadius: 10,
                        pointBorderWidth: 10,
                        pointBackgroundColor: 'transparent',
                        pointHoverBackgroundColor: 'rgba(0, 0, 0, 0.27)',
                        pointBorderColor: 'transparent',

                        lineTension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,

                animation: {
                    duration: _animate ? 1000 : false
                },

                tooltips: {
                    enabled: true,
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var label = data.datasets[tooltipItem.datasetIndex].label || ''

                            if (label) {
                                label += ': '
                            }
                            label += parseFloat(tooltipItem.yLabel / 1000) + 'k'
                            return  " " + label
                        },
                    }
                },

                scales: {
                    yAxes: [
                        {
                            ticks: {
                                fontFamily: "Open Sans",
                                fontColor: "#95909e",
                                fontStyle: "normal",
                                fontSize: "13",
                                beginAtZero: false,
                                maxTicksLimit: 6,
                                padding: 12,
                                callback: function(value, index, values) {
                                    var val = parseInt(value / 1000)
                                    return val > 0 ? val + 'k' : val
                                }
                            },
                            gridLines: {
                                drawBorder: false,

                                borderDash: [2, 4],
                                color: '#cbd1d5'
                            }
                        }
                    ],

                    xAxes: [
                        {
                            gridLines: {
                                display: false,
                                borderDash: [2, 2],
                                tickMarkLength: 16,
                                color: '#dbe1e5'
                            },
                            ticks: {
                                fontFamily: "Open Sans",
                                fontColor: "#95909e",
                                fontSize: "13",
                                padding: 0,
                                scaleBeginAtZero : true
                            }
                        },
                    ]
                },

                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        generateLabels: function(chart) {
                            labels = Chart.defaults.global.legend.labels.generateLabels(chart)
                            labels[0].fillStyle = '#75cad0'
                            labels[1].fillStyle = '#5dc7fe'
                            return labels;
                        }
                    }
                },
            }
        })



        ///////////////////////////////
        // the task progress circles

        $('canvas.task-progress').each(function() {
            var color = $(this).addClass('opacity-2').css('color')

            new Chart(this.getContext('2d'), {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [$(this).data('percent'), 100 - $(this).data('percent')],
                        backgroundColor: [
                            color,
                            "#e3e5ea"
                        ],
                        hoverBackgroundColor: [
                            color,
                            "#e3e5ea"
                        ],
                        borderWidth: 2
                    }]
                },

                options: {
                    responsive: false,
                    cutoutPercentage: 80,
                    legend: {
                        display: false
                    },
                    animation: {
                        duration: _animate ? 500 : false,
                        easing: 'easeInCubic'
                    },
                    tooltips: {
                        enabled: false,
                    }
                }
            })

        })



//////////////////////////////
// FullCalendar
        if (!window.Intl) {
            console.log("Calendar can't be displayed because your browser doesn's support `Intl`. You may use a polyfill!")
            return
        }

        FullCalendar.BootstrapTheme.prototype.classes = {
            root: 'fc-theme-bootstrap',
            table: 'table-bordered table-bordered brc-default-l2 text-secondary-d1 h-95',
            tableCellShaded: 'bgc-secondary-l3',
            buttonGroup: 'btn-group',
            button: 'btn btn-white btn-h-lighter-blue btn-a-blue',
            buttonActive: 'active',
            popover: 'card card-primary',
            popoverHeader: 'card-header',
            popoverContent: 'card-body',
        }
        FullCalendar.BootstrapTheme.prototype.baseIconClass = 'fa'
        FullCalendar.BootstrapTheme.prototype.iconClasses = {
            close: 'fa-times',
            prev: 'fa-chevron-left',
            next: 'fa-chevron-right',
            prevYear: 'fa-angle-double-left',
            nextYear: 'fa-angle-double-right'
        }
        FullCalendar.BootstrapTheme.prototype.iconOverrideOption = 'FontAwesome'
        FullCalendar.BootstrapTheme.prototype.iconOverrideCustomButtonOption = 'FontAwesome'
        FullCalendar.BootstrapTheme.prototype.iconOverridePrefix = 'fa-'

        // for some random events to be added
        var date = new Date()
        var m = date.getMonth()
        var y = date.getFullYear()

        var day1 = Math.random() * 20 + 2
        var day2 = Math.random() * 25 + 1
        var day3 = Math.random() * 15 + 1

        // initialize the calendar
        var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
            themeSystem: 'bootstrap',

            headerToolbar: {
                start: 'prev,next today',
                center: 'title',
                end: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
            },

            events: [
                {
                    title: 'Some Event',
                    description: 'Event 1 description',
                    start: new Date(y, m, 1, Math.random() * 23 + 1),
                    className: 'brc-red border-l-3 radius-0 text-dark-tp2 bgc-red-l3 text-95 px-2 py-1'
                },
                {
                    title: 'Long Event',
                    description: 'Event 2 description',
                    start: new Date(y, m, day1, Math.random() * 23 + 1),
                    end: new Date(y, m, day1 + 4, Math.random() * 23 + 1),
                    className: 'brc-green border-l-3 radius-0 text-dark-tp2 bgc-green-l3 text-95 px-2 py-1'
                },
                {
                    title: 'Other Event',
                    description: 'Event 3 description',
                    start: new Date(y, m, day2, Math.random() * 23 + 1),
                    allDay: false,
                    className: 'brc-blue border-l-3 radius-0 text-dark-tp2 bgc-blue-l3 text-95 px-2 py-1'
                },
                {
                    title: 'More Events',
                    description: 'Event 4 description',
                    start: new Date(y, m, day3, Math.random() * 10 + 1),
                    end: new Date(y, m, day3 + 1, Math.random() * 10 + 1),
                    className: 'brc-purple border-l-3 radius-0 text-dark-tp2 bgc-purple-l3 text-95 px-2 py-1'
                },
            ],

            eventDidMount: function(info) {
                // enable popovers on hover for events
                $(info.el).find('.fc-title').append("<span class='d-block mt-1 text-95 text-dark-tp3'>" + info.event.extendedProps.description + "</span>");
                $(info.el).popover({
                    title: info.event.title,
                    content: info.event.extendedProps.description,
                    placement: 'auto',
                    trigger: 'hover',
                    container: 'body'
                })
            }

        })

        calendar.render()

    })
</script>
</div>
</body>
<footer style="background-color: #022B61;">
    <div class="col-12 p-0 " style="background-color: #022B61; color: white;text-align: center;border-top: 23px solid #f7f7f7;height: 71px;">
        <div class="col-12 pt-3">
            <strong>Copyright &copy; 2019-<?= date('Y') ?> <a href="<?php echo base_url();?>admin/dashboard/"><?php echo $this->config->item('domainname');?></a> <?php echo COMPANYNAME?>.</strong> All rights reserved.
        </div>

    </div>
</footer>
</html>
