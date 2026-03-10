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
                                  <li><a href="<?= base_url('logout') ?>" class="dropdown-item"><i class="md md-settings-power mr-2"></i> Logout</a></li>
                                </ul>
                            </div>
                            
                            <p class="text-muted m-0"><?= session()->get('role') ?? 'Invité' ?></p>
                        </div>
                    </div>
                    <!--- Divider -->
                    <div id="sidebar-menu">
    <ul>
        <!-- Tableau de bord -->
        <li class="<?= (uri_string() == 'dashboard') ? 'active' : '' ?>">
            <a href="<?= base_url('/dashboard')?>" class="waves-effect">
                <i class="md md-home"></i>
                <span>Tableau de bord</span>
            </a>
        </li>

        <!-- Facilitateur -->
        <li class="<?= (uri_string() == 'facilitateur') ? 'active' : '' ?>">
            <a href="<?= base_url('facilitateur')?>" class="waves-effect">
                <i class="md md-person"></i>
                <span>Facilitateur</span>
            </a>
        </li>

        <!-- Superviseur -->
        <li class="<?= (uri_string() == 'superviseur') ? 'active' : '' ?>">
            <a href="<?= base_url('superviseur')?>" class="waves-effect">
                <i class="md md-people"></i>
                <span>Superviseur</span>
            </a>
        </li>

        <!-- Opérateur -->
        <li class="<?= (uri_string() == 'operateur') ? 'active' : '' ?>">
            <a href="<?= base_url('operateur')?>" class="waves-effect">
                <i class="md md-laptop"></i>
                <span>Opérateur</span>
            </a>
        </li>

    

        <!-- Paramètres (visible seulement admin) -->
        <?php if(session()->get('role_id') == 1): ?>
        <li class="has_sub <?= (in_array(uri_string(), ['users','profils','tables-editable'])) ? 'active' : '' ?>">
            <a href="javascript:void(0);" class="waves-effect">
                <i class="md md-settings"></i>
                <span>Paramètres</span>
                <span class="pull-right"><i class="md md-add"></i></span>
            </a>
            <ul class="list-unstyled">
                <li><a href="<?= base_url('users')?>">Gestion users</a></li>
                <li><a href="<?= base_url('profils')?>">Gestion profil</a></li>
            </ul>
        </li>
        <?php endif; ?>
    </ul>
    <div class="clearfix"></div>
</div>
                    <div class="clearfix"></div>
                </div><div class="slimScrollBar" style="background: rgb(122, 134, 143); width: 5px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px; height: 680.923px; visibility: visible;"></div><div class="slimScrollRail" style="width: 5px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div></div>
            </div>