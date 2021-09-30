<?php

namespace Deployer;

require 'recipe/common.php';

set('ms_consumer_threads', 5);
set('gs_consumer_threads', 10);
set('bs_consumer_threads', 5);
set('ps_consumer_threads', 5);

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

task('consumers:ms:stop', function () {
    run("sudo /usr/bin/systemctl stop ms-consumer@*");
})->onRoles('app');

task('consumers:ms:start', function () {
    $numprocs = get('ms_consumer_threads');
    for ($counter = 1; $counter <= $numprocs; $counter++) {
        run("sudo /usr/bin/systemctl start ms-consumer@" . $counter);
    }
})->onRoles('app');

task('consumers:gs:stop', function () {
    run("sudo /usr/bin/systemctl stop gs-consumer@*");
})->onRoles('app');

task('consumers:gs:start', function () {
    $numprocs = get('gs_consumer_threads');
    for ($counter = 1; $counter <= $numprocs; $counter++) {
        run("sudo /usr/bin/systemctl start gs-consumer@" . $counter);
    }
})->onRoles('app');

task('consumers:bs:stop', function () {
    run("sudo /usr/bin/systemctl stop bs-consumer@*");
})->onRoles('app');

task('consumers:bs:start', function () {
    $numprocs = get('bs_consumer_threads');
    for ($counter = 1; $counter <= $numprocs; $counter++) {
        run("sudo /usr/bin/systemctl start bs-consumer@" . $counter);
    }
})->onRoles('app');

task('consumers:ps:stop', function () {
    run("sudo /usr/bin/systemctl stop ps-consumer@*");
})->onRoles('app');

task('consumers:ps:start', function () {
    $numprocs = get('ps_consumer_threads');
    for ($counter = 1; $counter <= $numprocs; $counter++) {
        run("sudo /usr/bin/systemctl start ps-consumer@" . $counter);
    }
})->onRoles('app');

task('deploy:migratedb', function () {
    run('{{bin/php}} {{release_path}}/artisan migrate');
})->onRoles('app')->once();

task('consumers:ms:restart', [
    'consumers:ms:stop',
    'consumers:ms:start'
])->onRoles('app');

task('consumers:gs:restart', [
    'consumers:gs:stop',
    'consumers:gs:start'
])->onRoles('app');

task('consumers:bs:restart', [
    'consumers:bs:stop',
    'consumers:bs:start'
])->onRoles('app');

task('consumers:ps:restart', [
    'consumers:ps:stop',
    'consumers:ps:start'
])->onRoles('app');

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
after('deploy:symlink', 'consumers:ms:restart');
after('deploy:symlink', 'consumers:gs:restart');
after('deploy:symlink', 'consumers:bs:restart');
after('deploy:symlink', 'consumers:ps:restart');
after('deploy:symlink', 'cachetool:clear:opcache');
after('rollback', 'consumers:ms:restart');
after('rollback', 'consumers:gs:restart');
after('rollback', 'consumers:bs:restart');
after('rollback', 'consumers:ps:restart');
after('rollback', 'cachetool:clear:opcache');

// If deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
