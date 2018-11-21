@servers(['web-1' => '119.29.253.94'])

@task('deploy', ['on' => ['web-1']])
cd site
git pull origin {{ $branch }}
php artisan migrate
@endtask