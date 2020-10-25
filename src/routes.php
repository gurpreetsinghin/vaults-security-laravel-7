<?php
Route::group(['prefix' => 'vaults-security', 'namespace' => 'Gurpreetsinghin\VaultsSecurity\Controllers', 'middleware' => ['web']], function(){

    Route::get('/', function(){
        if(!auth()->check()){
            return redirect(route('ps.admin.login'));
        }else{
            return redirect(route('ps.admin.dashboard'));
        }
    });

    Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function(){

        Route::name('ps.admin.install')->any('install', 'AuthController@addUser');
        Route::name('ps.admin.login')->any('login', 'AuthController@login');
        Route::name('ps.admin.logout')->get('logout', 'AuthController@logout');

        Route::group(['middleware' => ['web', 'auth:project-security-admin']], function(){
            Route::get('dashboard', 'MainController@dashboard')->name('ps.admin.dashboard');
            Route::get('site-info', 'SiteInfoController@index')->name('ps.admin.site-info');
            Route::any('settings', 'MainController@settings')->name('ps.admin.settings');
            Route::any('account', 'MainController@account')->name('ps.admin.account');
            Route::any('visit-analytics', 'MainController@visitAnalytics')->name('ps.admin.visit-analytics');
            Route::any('ip-lookup', 'MainController@ipLookup')->name('ps.admin.ip-lookup');

            Route::group(['prefix' => 'whitelist'], function(){
                Route::get('ip', 'WhitelistController@ip')->name('ps.admin.whitelist.ip');
                Route::post('ip-add', 'WhitelistController@ipAdd')->name('ps.admin.whitelist.ip-add');
                Route::any('ip-edit/{id}', 'WhitelistController@ipEdit')->name('ps.admin.whitelist.ip-edit');
                Route::get('ip-delete/{id}', 'WhitelistController@ipDelete')->name('ps.admin.whitelist.ip-delete');

                Route::get('files', 'WhitelistController@files')->name('ps.admin.whitelist.file');
                Route::post('file-add', 'WhitelistController@fileAdd')->name('ps.admin.whitelist.file-add');
                Route::any('file-edit/{id}', 'WhitelistController@fileEdit')->name('ps.admin.whitelist.file-edit');
                Route::get('file-delete/{id}', 'WhitelistController@fileDelete')->name('ps.admin.whitelist.file-delete');
            });

            Route::any('warning-pages', 'WarningPagesController@index')->name('ps.admin.warning-pages');
            Route::get('login-history', 'MainController@loginHistory')->name('ps.admin.login-history');
            Route::any('sql-injection', 'MainController@sqlInjection')->name('ps.admin.sql-injection');
            Route::any('badbots', 'MainController@badbots')->name('ps.admin.badbots');
            Route::any('proxy', 'MainController@proxy')->name('ps.admin.proxy');

            Route::group(['prefix' => 'spam'], function(){
                Route::any('', 'SpamController@spam')->name('ps.admin.spam');
                Route::post('add-db', 'SpamController@addDb')->name('ps.admin.add-db');
                Route::get('delete-db/{id}', 'SpamController@deleteDb')->name('ps.admin.delete-db');
            });

            Route::any('adblocker-detection', 'MainController@adblockerDetection')->name('ps.admin.adblocker-detection');

            Route::group(['prefix' => 'bad-words'], function(){
                Route::any('', 'BadWordsController@index')->name('ps.admin.bad-words');
                Route::post('add', 'BadWordsController@add')->name('ps.admin.bad-words.add');
                Route::post('replace', 'BadWordsController@replace')->name('ps.admin.bad-words.replace');
                Route::get('delete/{id}', 'BadWordsController@delete')->name('ps.admin.bad-words.delete');
            });

            Route::group(['prefix' => 'logs'], function(){
                Route::get('', 'LogsController@index')->name('ps.admin.logs');
                Route::any('view/{id}', 'LogsController@view')->name('ps.admin.view');
                Route::get('unban-ip/{id}', 'LogsController@unbanIp')->name('ps.admin.unban-ip');
                Route::get('ban-ip/{id}', 'LogsController@banIp')->name('ps.admin.ban-ip');
                Route::get('delete/{id}', 'LogsController@delete')->name('ps.admin.logs.delete');
                Route::get('delete-all', 'LogsController@deleteAll')->name('ps.admin.logs.delete-all');
            });

            Route::group(['prefix' => 'sqli-logs'], function(){
                Route::get('', 'SqliLogsController@index')->name('ps.admin.sqli-logs');
                Route::any('view/{id}', 'SqliLogsController@view')->name('ps.admin.sqli-logs.view');
                Route::get('unban-ip/{id}', 'SqliLogsController@unbanIp')->name('ps.admin.sqli-logs.unban-ip');
                Route::get('ban-ip/{id}', 'SqliLogsController@banIp')->name('ps.admin.sqli-logs.ban-ip');
                Route::get('delete/{id}', 'SqliLogsController@delete')->name('ps.admin.sqli-logs.delete');
                Route::get('delete-all', 'SqliLogsController@deleteAll')->name('ps.admin.sqli-logs.delete-all');
            });

            Route::group(['prefix' => 'badbot-logs'], function(){
                Route::get('', 'BadbotLogsController@index')->name('ps.admin.badbot-logs');
                Route::any('view/{id}', 'BadbotLogsController@view')->name('ps.admin.badbot-logs.view');
                Route::get('unban-ip/{id}', 'BadbotLogsController@unbanIp')->name('ps.admin.badbot-logs.unban-ip');
                Route::get('ban-ip/{id}', 'BadbotLogsController@banIp')->name('ps.admin.badbot-logs.ban-ip');
                Route::get('delete/{id}', 'BadbotLogsController@delete')->name('ps.admin.badbot-logs.delete');
                Route::get('delete-all', 'BadbotLogsController@deleteAll')->name('ps.admin.badbot-logs.delete-all');
            });

            Route::group(['prefix' => 'proxy-logs'], function(){
                Route::get('', 'ProxyLogsController@index')->name('ps.admin.proxy-logs');
                Route::any('view/{id}', 'ProxyLogsController@view')->name('ps.admin.proxy-logs.view');
                Route::get('unban-ip/{id}', 'ProxyLogsController@unbanIp')->name('ps.admin.proxy-logs.unban-ip');
                Route::get('ban-ip/{id}', 'ProxyLogsController@banIp')->name('ps.admin.proxy-logs.ban-ip');
                Route::get('delete/{id}', 'ProxyLogsController@delete')->name('ps.admin.proxy-logs.delete');
                Route::get('delete-all', 'ProxyLogsController@deleteAll')->name('ps.admin.proxy-logs.delete-all');
            });

            Route::group(['prefix' => 'spammer-logs'], function(){
                Route::get('', 'SpammerLogsController@index')->name('ps.admin.spammer-logs');
                Route::any('view/{id}', 'SpammerLogsController@view')->name('ps.admin.spammer-logs.view');
                Route::get('unban-ip/{id}', 'SpammerLogsController@unbanIp')->name('ps.admin.spammer-logs.unban-ip');
                Route::get('ban-ip/{id}', 'SpammerLogsController@banIp')->name('ps.admin.spammer-logs.ban-ip');
                Route::get('delete/{id}', 'SpammerLogsController@delete')->name('ps.admin.spammer-logs.delete');
                Route::get('delete-all', 'SpammerLogsController@deleteAll')->name('ps.admin.spammer-logs.delete-all');
            });

            Route::group(['prefix' => 'live-traffic'], function(){
                Route::get('', 'LiveTrafficController@index')->name('ps.admin.live-traffic');
                Route::any('view/{id}', 'LiveTrafficController@view')->name('ps.admin.live-traffic.view');
                Route::get('delete-all', 'LiveTrafficController@deleteAll')->name('ps.admin.live-traffic.delete-all');
                Route::post('change-status', 'LiveTrafficController@changeStatus')->name('ps.admin.live-traffic.change-status');
            });

            Route::group(['prefix' => 'ip-ban'], function(){
                Route::get('', 'ipBanController@index')->name('ps.admin.ip-ban');
                Route::post('add', 'ipBanController@add')->name('ps.admin.ip-ban.add');
                Route::any('edit/{id}', 'ipBanController@edit')->name('ps.admin.ip-ban.edit');
                Route::any('delete/{id}', 'ipBanController@delete')->name('ps.admin.ip-ban.delete');
                Route::any('delete-all', 'ipBanController@deleteAll')->name('ps.admin.ip-ban.delete-all');
            });

            Route::group(['prefix' => 'country-ban'], function(){
                Route::get('', 'countryBanController@index')->name('ps.admin.country-ban');
                Route::post('add', 'countryBanController@add')->name('ps.admin.country-ban.add');
                Route::any('edit/{id}', 'countryBanController@edit')->name('ps.admin.country-ban.edit');
                Route::any('delete/{id}', 'countryBanController@delete')->name('ps.admin.country-ban.delete');
            });

            Route::group(['prefix' => 'iprange-ban'], function(){
                Route::get('', 'IprangeBanController@index')->name('ps.admin.iprange-ban');
                Route::post('add', 'IprangeBanController@add')->name('ps.admin.iprange-ban.add');
                Route::any('edit/{id}', 'IprangeBanController@edit')->name('ps.admin.iprange-ban.edit');
                Route::any('delete/{id}', 'IprangeBanController@delete')->name('ps.admin.iprange-ban.delete');
                Route::any('delete-all', 'IprangeBanController@deleteAll')->name('ps.admin.iprange-ban.delete-all');
            });

            Route::group(['prefix' => 'other-ban'], function(){
                Route::get('', 'OtherBanController@index')->name('ps.admin.other-ban');
                Route::post('add', 'OtherBanController@add')->name('ps.admin.other-ban.add');
                Route::any('delete/{id}', 'OtherBanController@delete')->name('ps.admin.other-ban.delete');
            });

            Route::any('php-functions-check', 'MainController@phpFunctionsCheck')->name('ps.admin.php-functions-check');

            Route::group(['prefix' => 'php-config-check'], function(){
                Route::any('', 'PhpConfigCheckController@index')->name('ps.admin.php-config-check');
            });

            Route::any('error-monitoring', 'MainController@errorMonitoring')->name('ps.admin.error-monitoring');
            Route::get('port-scanner', 'MainController@portScanner')->name('ps.admin.port-scanner');
            Route::any('blacklist-checker', 'MainController@blacklistChecker')->name('ps.admin.blacklist-checker');
            Route::any('hashing', 'MainController@hashing')->name('ps.admin.hashing');
        });
    });

    Route::group(['namespace' => 'Site'], function(){
        Route::name('ps.site.banned')->get('banned', 'PagesController@banned');
        Route::name('ps.site.banned-country')->get('banned-country', 'PagesController@bannedCountry');
        Route::name('ps.site.blocked-browser')->get('blocked-browser', 'PagesController@blockedBrowser');
        Route::name('ps.site.blocked-os')->get('blocked-os', 'PagesController@blockedOs');
        Route::name('ps.site.blocked-isp')->get('blocked-isp', 'PagesController@blockedIsp');
        Route::name('ps.site.blocked-referer')->get('blocked-referer', 'PagesController@blockedReferer');
        Route::name('ps.site.badbot-detected')->get('badbot-detected', 'PagesController@badbotDetected');
        Route::name('ps.site.fakebot-detected')->get('fakebot-detected', 'PagesController@fakebotDetected');
        Route::name('ps.site.missing-useragent')->get('missing-useragent', 'PagesController@missingUseragent');
        Route::name('ps.site.invalid-ip')->get('invalid-ip', 'PagesController@invalidIp');
    });
});

?>