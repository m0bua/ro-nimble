<?php

namespace Deployer;

require 'recipe/common.php';

// Project name
set('application', 'ivv-nimble');
set('keep_releases', 5);
set('default_timeout', null);

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', false);

// Shared files/dirs between deploys
add('shared_dirs', ['storage']);

// Writable dirs by web server
set('writable_mode', 'chown');
set('writable_use_sudo', true);

######## PROD ENV ###########
host('10.10.13.45')
    ->stage('prod')
    ->roles('app')
    ->set('deploy_path', '/var/www/ivv-nimble')
    ->user('rzk')
    ->port(10022)
    ->multiplexing(true)
    ->addSshOption('StrictHostKeyChecking', 'no');

host('10.10.12.221')
    ->stage('prod')
    ->roles('api')
    ->set('deploy_path', '/var/www/ivv-nimble')
    ->user('rzk')
    ->port(10022)
    ->multiplexing(true)
    ->addSshOption('StrictHostKeyChecking', 'no');

######## PREPROD ENV ###########
host('10.10.16.191')
    ->stage('preprod')
    ->roles('app', 'api')
    ->set('deploy_path', '/var/www/ivv-nimble')
    ->user('rzk')
    ->port(10022)
    ->multiplexing(true)
    ->addSshOption('StrictHostKeyChecking', 'no');


host('10.10.60.47')
    ->stage('pld-prod')
    ->roles('app')
    ->set('deploy_path', '/var/www/ivv-nimble')
    ->user('rzk')
    ->port(10022)
    ->multiplexing(true)
    ->addSshOption('StrictHostKeyChecking', 'no');

host('10.10.60.46')
    ->stage('pld-prod')
    ->roles('api')
    ->set('deploy_path', '/var/www/ivv-nimble')
    ->user('rzk')
    ->port(10022)
    ->multiplexing(true)
    ->addSshOption('StrictHostKeyChecking', 'no');



######## TEST ENV ###########
host('10.10.29.66')
    ->stage('dev')
    ->roles('app', 'api')
    ->set('deploy_path', '/var/www/ivv-nimble')
    ->user('rzk')
    ->port(10022)
    ->multiplexing(true)
    ->addSshOption('StrictHostKeyChecking', 'no');

######## DEPLOY TASKS ###########
task('build', function () {
    run('composer-v1 install');
})->local();

task('supervisor:update', function () {
    run("/usr/bin/supervisorctl update");
})->onRoles('app');

task('supervisor:restart', function () {
    run("/usr/bin/supervisorctl restart all");
})->onRoles('app');

task('deploy:migratedb', function () {
    run('{{bin/php}} {{release_path}}/artisan migrate --force');
})->onRoles('app')->once();

task('cachetool:clear:opcache', function () {
    run("/usr/local/bin/cachetool opcache:reset --fcgi=127.0.0.1:9000 2>&1 || true");
})->onRoles('api');

task('upload', function () {
    upload(__DIR__ . '/', '{{release_path}}');
})->desc('Environment setup');

######## DEPLOY FLOW ###########
task('release', [
    'deploy:prepare',
    'deploy:release',
    'upload',
    'deploy:shared',
    'deploy:writable',
    'deploy:migratedb',
]);

task('symlink', [
    'deploy:symlink',
]);

task('deploy', [
    'release',
    'symlink',
    'cleanup',
    'success'
]);

######## DEPLOY AFTER ###########
after('deploy:symlink', 'supervisor:update');
after('deploy:symlink', 'cachetool:clear:opcache');
after('rollback', 'supervisor:update');
after('rollback', 'cachetool:clear:opcache');

// If deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
