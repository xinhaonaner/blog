<?php

namespace Canvas\Http\Controllers\Backend;

use Excel;
use Session;
use Canvas\Models\Tag;
use Canvas\Models\Post;
use Canvas\Models\User;
use Canvas\Models\PostTag;
use Canvas\Models\Settings;
use Canvas\Models\Migrations;
use Canvas\Helpers\CanvasHelper;
use Canvas\Models\PasswordResets;
use Illuminate\Support\Facades\App;
use Canvas\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class ToolsController extends Controller
{
    protected $date;

    public function __construct()
    {
        $this->date = date('Y-m-d');
    }

    /**
     * Display the tools page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $data = [
            'status' => App::isDownForMaintenance() ? CanvasHelper::MAINTENANCE_MODE_ENABLED : CanvasHelper::MAINTENANCE_MODE_DISABLED,
        ];

        return view('canvas::backend.tools.index', compact('data'));
    }

    /**
     * Manually Flush the Application Cache.
     *
     * @return \Illuminate\View\View
     */
    public function clearCache()
    {
        $exitCode = Artisan::call('cache:clear');
        $exitCode = Artisan::call('route:clear');
        $exitCode = Artisan::call('optimize');
        if ($exitCode === 0) {
            Session::put('_cache-clear', trans('canvas::messages.cache_clear_success'));
        } else {
            Session::put('_cache-clear', trans('canvas::messages.cache_clear_error'));
        }

        return redirect()->route('canvas.admin.tools');
    }

    /**
     * Create and download an archive of all existing data.
     *
     * @return \Illuminate\View\View
     */
    public function handleDownload()
    {
        $this->storeUsers();
        $this->storePosts();
        $this->storeTags();
        $this->storePostTag();
        $this->storeMigrations();
        $this->storeUploads();
        $this->storePasswordResets();
        $this->storeSettings();
        $date = date('Y-m-d');
        $path = storage_path($date.'-canvas-archive');
        $filename = sprintf('%s.zip', $path);
        $zip = new \ZipArchive();
        $zip->open($filename, \ZipArchive::CREATE);
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );
        foreach ($files as $name => $file) {
            if (! $file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($path) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }
        $zip->close();
        \File::deleteDirectory(storage_path($date.'-canvas-archive'));

        return response()->download(storage_path($date.'-canvas-archive.zip'))->deleteFileAfterSend(true);
    }

    protected function storeUsers()
    {
        Excel::create('users', function ($excel) {
            $excel->sheet('Users', function ($sheet) {
                $users = User::get()->toArray();
                $sheet->appendRow(array_keys($users[0]));
                foreach ($users as $user) {
                    $sheet->appendRow($user);
                }
            });
        })->store('csv', storage_path($this->date.'-canvas-archive'), true);
    }

    protected function storePosts()
    {
        $posts = Post::get()->toArray();
        if ($posts != []) {
            Excel::create('posts', function ($excel) {
                $excel->sheet('Posts', function ($sheet) {
                    $posts = Post::get()->toArray();
                    $sheet->appendRow(array_keys($posts[0]));
                    foreach ($posts as $post) {
                        $sheet->appendRow($post);
                    }
                });
            })->store('csv', storage_path($this->date.'-canvas-archive'), true);
        }
    }

    protected function storeTags()
    {
        $tags = Tag::get()->toArray();
        if ($tags != []) {
            Excel::create('tags', function ($excel) {
                $excel->sheet('Tags', function ($sheet) {
                    $tags = Tag::get()->toArray();
                    $sheet->appendRow(array_keys($tags[0]));
                    foreach ($tags as $tag) {
                        $sheet->appendRow($tag);
                    }
                });
            })->store('csv', storage_path($this->date.'-canvas-archive'), true);
        }
    }

    protected function storePostTag()
    {
        $postTag = PostTag::get()->toArray();
        if ($postTag != []) {
            Excel::create('post_tag', function ($excel) {
                $excel->sheet('PostTag', function ($sheet) {
                    $postTag = PostTag::get()->toArray();
                    $sheet->appendRow(array_keys($postTag[0]));
                    foreach ($postTag as $pt) {
                        $sheet->appendRow($pt);
                    }
                });
            })->store('csv', storage_path($this->date.'-canvas-archive'), true);
        }
    }

    protected function storeMigrations()
    {
        Excel::create('migrations', function ($excel) {
            $excel->sheet('Migrations', function ($sheet) {
                $migrations = Migrations::get()->toArray();
                $sheet->appendRow(array_keys($migrations[0]));
                foreach ($migrations as $migration) {
                    $sheet->appendRow($migration);
                }
            });
        })->store('csv', storage_path($this->date.'-canvas-archive'), true);
    }

    protected function storePasswordResets()
    {
        $password_resets = PasswordResets::get()->toArray();
        if ($password_resets != []) {
            Excel::create('password_resets', function ($excel) {
                $excel->sheet('PasswordResets', function ($sheet) {
                    $sheet->appendRow(array_keys($password_resets[0]));
                    foreach ($password_resets as $password_reset) {
                        $sheet->appendRow($password_reset);
                    }
                });
            })->store('csv', storage_path($this->date.'-canvas-archive'), true);
        }
    }

    protected function storeSettings()
    {
        Excel::create('settings', function ($excel) {
            $excel->sheet('Settings', function ($sheet) {
                $settings = Settings::get()->toArray();
                $sheet->appendRow(array_keys($settings[0]));
                foreach ($settings as $setting) {
                    $sheet->appendRow($setting);
                }
            });
        })->store('csv', storage_path($this->date.'-canvas-archive'), true);
    }

    protected function storeUploads()
    {
        $source = storage_path('app/public');
        $destination = storage_path($this->date.'-canvas-archive/uploads/');

        return \File::copyDirectory($source, $destination);
    }

    /**
     * Enable Application Maintenance Mode.
     *
     * @return \Illuminate\View\View
     */
    public function enableMaintenanceMode()
    {
        $exitCode = Artisan::call('down');
        if ($exitCode === 0) {
            Session::put('admin_ip', request()->ip());
            Session::put('_enable-maintenance-mode', trans('canvas::messages.enable_maintenance_mode_success'));
        } else {
            Session::put('_enable-maintenance-mode', trans('canvas::messages.enable_maintenance_mode_error'));
        }

        return redirect()->route('canvas.admin.tools');
    }

    /**
     * Disable Application Maintenance Mode.
     *
     * @return \Illuminate\View\View
     */
    public function disableMaintenanceMode()
    {
        $exitCode = Artisan::call('up');
        if ($exitCode === 0) {
            Session::put('_disable-maintenance-mode', trans('canvas::messages.disable_maintenance_mode_success'));
        } else {
            Session::put('_disable-maintenance-mode', trans('canvas::messages.disable_maintenance_mode_error'));
        }

        return redirect()->route('canvas.admin.tools');
    }
}
