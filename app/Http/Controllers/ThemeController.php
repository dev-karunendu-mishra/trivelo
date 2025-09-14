<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\ThemeService;
use App\Models\Theme;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ThemeController extends Controller
{
    use AuthorizesRequests;
    
    protected $themeService;

    public function __construct(ThemeService $themeService)
    {
        $this->themeService = $themeService;
    }

    /**
     * Display all available themes
     */
    public function index()
    {
        $themes = $this->themeService->getAvailableThemes();
        $activeTheme = $this->themeService->getActiveTheme();

        return view('themes.index', compact('themes', 'activeTheme'));
    }

    /**
     * Switch to a different theme
     */
    public function switch(Request $request)
    {
        Log::info('Theme switch request received', $request->all());
        
        $request->validate([
            'theme' => 'required|string|exists:themes,name'
        ]);

        $themeName = $request->input('theme');
        Log::info('Switching to theme: ' . $themeName);
        
        try {
            $this->themeService->switchTheme($themeName);
            Log::info('Theme switched successfully to: ' . $themeName);
            
            return response()->json([
                'success' => true,
                'message' => 'Theme switched successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Theme switch error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to switch theme: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get current theme configuration
     */
    public function current()
    {
        return response()->json([
            'theme' => $this->themeService->getThemeConfig(),
            'css_variables' => $this->themeService->getThemeCssVariables(),
            'fonts' => $this->themeService->getThemeFonts()
        ]);
    }

    /**
     * Preview a theme
     */
    public function preview(Theme $theme)
    {
        return view('themes.preview', compact('theme'));
    }

    /**
     * Get theme settings for customization
     */
    public function settings()
    {
        $this->authorize('manage themes');

        $settings = $this->themeService->getThemeSettings();
        $activeTheme = $this->themeService->getActiveTheme();

        return view('admin.themes.settings', compact('settings', 'activeTheme'));
    }

    /**
     * Update theme settings
     */
    public function updateSettings(Request $request)
    {
        $this->authorize('manage themes');

        $request->validate([
            'settings' => 'required|array'
        ]);

        $this->themeService->updateThemeSettings($request->settings);

        return redirect()->back()->with('success', 'Theme settings updated successfully');
    }

    /**
     * Export theme configuration
     */
    public function export(Theme $theme)
    {
        $this->authorize('manage themes');

        $config = $this->themeService->exportTheme($theme);
        $filename = "theme-{$theme->name}-" . date('Y-m-d') . '.json';

        return response()->json($config)
                         ->header('Content-Disposition', "attachment; filename={$filename}");
    }

    /**
     * Import theme configuration
     */
    public function import(Request $request)
    {
        $this->authorize('manage themes');

        $request->validate([
            'theme_file' => 'required|file|mimes:json'
        ]);

        try {
            $content = file_get_contents($request->file('theme_file')->getRealPath());
            $themeData = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \InvalidArgumentException('Invalid JSON file');
            }

            $theme = $this->themeService->importTheme($themeData);

            return redirect()->back()->with('success', "Theme '{$theme->display_name}' imported successfully");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to import theme: ' . $e->getMessage());
        }
    }

    /**
     * Create a new custom theme
     */
    public function create()
    {
        $this->authorize('manage themes');

        return view('admin.themes.create');
    }

    /**
     * Store a new custom theme
     */
    public function store(Request $request)
    {
        $this->authorize('manage themes');

        $request->validate([
            'name' => 'required|string|unique:themes,name|max:50',
            'display_name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'color_scheme' => 'required|array',
            'typography_settings' => 'nullable|array',
            'layout_settings' => 'nullable|array',
        ]);

        $theme = $this->themeService->createCustomTheme($request->all());

        return redirect()->route('admin.themes.index')->with('success', "Theme '{$theme->display_name}' created successfully");
    }

    /**
     * Edit a theme
     */
    public function edit(Theme $theme)
    {
        $this->authorize('manage themes');

        return view('admin.themes.edit', compact('theme'));
    }

    /**
     * Update a theme
     */
    public function update(Request $request, Theme $theme)
    {
        $this->authorize('manage themes');

        $request->validate([
            'display_name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'color_scheme' => 'required|array',
            'typography_settings' => 'nullable|array',
            'layout_settings' => 'nullable|array',
        ]);

        $theme->update($request->all());

        if ($theme->is_active) {
            $this->themeService->clearCache();
        }

        return redirect()->route('admin.themes.index')->with('success', "Theme '{$theme->display_name}' updated successfully");
    }

    /**
     * Delete a theme
     */
    public function destroy(Theme $theme)
    {
        $this->authorize('manage themes');

        if ($theme->is_default) {
            return redirect()->back()->with('error', 'Cannot delete the default theme');
        }

        if ($theme->is_active) {
            // Switch to default theme before deleting
            $defaultTheme = Theme::getDefault();
            if ($defaultTheme && $defaultTheme->id !== $theme->id) {
                $defaultTheme->activate();
            }
        }

        $theme->delete();

        return redirect()->route('admin.themes.index')->with('success', "Theme '{$theme->display_name}' deleted successfully");
    }
}
