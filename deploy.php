<?php

namespace Deployer;

require 'recipe/common.php';

set('ms_consumer_threads', 5);
set('gs_consumer_threads', 10);

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

host('10.10.13.45')
    ->stage('prod')
    ->set('deploy_path', '/var/www/ivv-nimble')
    ->user('rzk')
    ->port(10022)
    ->multiplexing(true)
    ->addSshOption('StrictHostKeyChecking', 'no');

host('10.10.29.66')
    ->stage('dev')
    ->set('deploy_path', '/var/www/ivv-nimble')
    ->user('rzk')
    ->port(10022)
    ->multiplexing(true)
    ->addSshOption('StrictHostKeyChecking', 'no');

// Tasks
task('build', function () {
    run('composer.phar install');
})->local();

task('consumers:ms:stop', function () {
    run("sudo /usr/bin/systemctl stop ms-consumer@*");
});

task('consumers:ms:start', function () {
    $numprocs = get('ms_consumer_threads');
    for ($counter = 1; $counter <= $numprocs; $counter++) {
        run("sudo /usr/bin/systemctl start ms-consumer@" . $counter);
    }
});

task('consumers:gs:stop', function () {
    run("sudo /usr/bin/systemctl stop gs-consumer@*");
});

task('consumers:gs:start', function () {
    $numprocs = get('gs_consumer_threads');
    for ($counter = 1; $counter <= $numprocs; $counter++) {
        run("sudo /usr/bin/systemctl start gs-consumer@" . $counter);
    }
});

task('deploy:migratedb', function () {
    run('{{bin/php}} {{release_path}}/artisan migrate');
})->once();

task('consumers:ms:restart', [
    'consumers:ms:stop',
    'consumers:ms:start'
]);

task('consumers:gs:restart', [
    'consumers:gs:stop',
    'consumers:gs:start'
]);

task('upload', function () {
    upload(__DIR__ . '/', '{{release_path}}');
})->desc('Environment setup');

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

after('deploy:symlink', 'consumers:ms:restart');
after('deploy:symlink', 'consumers:gs:restart');
after('rollback', 'consumers:ms:restart');
after('rollback', 'consumers:gs:restart');

// [Optional] If deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
