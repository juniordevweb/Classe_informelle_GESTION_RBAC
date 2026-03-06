<?php
session()->get('role_id')
?>
<div class="left side-menu">
                <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 706px;"><div class="sidebar-inner slimscrollleft" style="overflow: hidden; width: auto; height: 706px;">
                    <div class="user-details">
                        <div class="pull-left">
                            <img src="<?= base_url('assets/images/users/avatar-1.jpg')?>" alt="" class="thumb-md rounded-circle">
                        </div>
                        <div class="user-info">
                            <div class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" _msttexthash="88855" _msthash="17">
                                        John Doe
                                </a>
                                <ul class="dropdown-menu" _msthidden="4">
                                    <li _msthidden="1"><a href="javascript:void(0)" class="dropdown-item" _msthidden="1"><i class="md md-face-unlock mr-2"></i><font _mstmutation="1" _msttexthash="94315" _msthidden="1" _msthash="18"> Profile</font><div class="ripple-wrapper"></div></a></li>
                                    <li _msthidden="1"><a href="javascript:void(0)" class="dropdown-item" _msthidden="1"><i class="md md-settings mr-2"></i><font _mstmutation="1" _msttexthash="117221" _msthidden="1" _msthash="19"> Settings</font></a></li>
                                    <li _msthidden="1"><a href="javascript:void(0)" class="dropdown-item" _msthidden="1"><i class="md md-lock mr-2"></i><font _mstmutation="1" _msttexthash="156065" _msthidden="1" _msthash="20"> Lock screen</font></a></li>
                                    <li _msthidden="1"><a href="javascript:void(0)" class="dropdown-item" _msthidden="1"><i class="md md-settings-power mr-2"></i><font _mstmutation="1" _msttexthash="79768" _msthidden="1" _msthash="21"> Logout</font></a></li>
                                </ul>
                            </div>
                            
                            <p class="text-muted m-0" _msttexthash="265590" _msthash="22">Administrateur</p>
                        </div>
                    </div>
                    <!--- Divider -->
                    <div id="sidebar-menu">
                        <ul>
                            <li class="active">
                                <a href="<?= base_url('/dashboard')?>" class="waves-effect active"><i class="md md-home"></i><span _msttexthash="226772" _msthash="23"> Tableau de bord </span></a>
                            </li>

                            <li class="">
                                <a href="<?= base_url('')?>" class="waves-effect active"><i class="md md-person"></i><span _msttexthash="226772" _msthash="23"> Facilitateur </span></a>
                            </li>

                            <li class="">
                                <a href="<?= base_url('')?>" class="waves-effect active"><i class="md md-people"></i><span _msttexthash="226772" _msthash="23"> Superviseur </span></a>
                            </li>

                            <li class="">
                                <a href="<?= base_url('')?>" class="waves-effect active"><i class="md md-laptop"></i><span _msttexthash="226772" _msthash="23"> Operateur</span></a>
                            </li>

                            
                            <li class="has_sub">
                                <a href="#" class="waves-effect"><i class="md md-now-widgets"></i><span _msttexthash="77805" _msthash="52"> Formes </span><span class="pull-right"><i class="md md-add"></i></span></a>
                                <ul class="list-unstyled" _msthidden="8">
                                    <li _msthidden="1"><a href="form-elements.html" _msttexthash="283725" _msthidden="1" _msthash="53">General Elements</a></li>
                                    <li _msthidden="1"><a href="form-validation.html" _msttexthash="256113" _msthidden="1" _msthash="54">Form Validation</a></li>
                                    <li _msthidden="1"><a href="form-advanced.html" _msttexthash="196612" _msthidden="1" _msthash="55">Advanced Form</a></li>
                                    <li _msthidden="1"><a href="form-wizard.html" _msttexthash="155545" _msthidden="1" _msthash="56">Form Wizard</a></li>
                    
                                </ul>
                            </li>
                            
                            
                             <?php if(session()->get('role_id') == 1): ?>
                            <li class="has_sub">
                                <a href="#" class="waves-effect"><i class="md md-settings"></i> <span _msttexthash="114426" _msthash="61"> Parametres </span><span class="pull-right"><i class="md md-add"></i></span></a>
                                <ul class="list-unstyled" _msthidden="4">
                                    <li _msthidden="1"><a href="<?= base_url('users')?>" _msttexthash="172172" _msthidden="1" _msthash="62">Gestion users</a></li>
                                    <li _msthidden="1"><a href="<?= base_url('profils')?>" _msttexthash="125515" _msthidden="1" _msthash="63">Gestion profil</a></li>
                                    <li _msthidden="1"><a href="tables-editable.html" _msttexthash="218270" _msthidden="1" _msthash="64">Editable Table</a></li>
                                </ul>
                            </li>
                            <?php endif; ?>

                           
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="clearfix"></div>
                </div><div class="slimScrollBar" style="background: rgb(122, 134, 143); width: 5px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px; height: 680.923px; visibility: visible;"></div><div class="slimScrollRail" style="width: 5px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div></div>
            </div>