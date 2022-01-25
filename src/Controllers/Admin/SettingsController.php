<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Adfm\Dev;
use App\Helpers\Adfm\Settings;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Screens\SettingsScreen;
use Illuminate\Http\Request;
use App\Models\Adfm\Land;
use Spatie\Valuestore\Valuestore;

class SettingsController extends Controller
{

    public function index(Settings $settings)
    {
        SettingsScreen::index($settings);
    }

    /**
     * Обновление
     */
    public function update(Request $request, Settings $settings)
    {
        $settings->put($request->all()['settings']);
        return redirect()->route('adfm.settings.index');
    }
}
