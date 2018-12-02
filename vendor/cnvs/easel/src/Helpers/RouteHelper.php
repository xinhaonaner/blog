<?php

namespace Canvas\Helpers;

class RouteHelper extends CanvasHelper
{
    /**
     * Get General Middleware.
     */
    public static function getGeneralMiddleware()
    {
        $config = ConfigHelper::getWriter();
        $val = $config->get('route_middleware_general');

        return is_null($val) ? self::ROUTE_MIDDLEWARE_GROUPS_GENERAL : $val;
    }

    /**
     * Get Safe Middleware.
     */
    public static function getInstalledMiddleware()
    {
        $config = ConfigHelper::getWriter();
        $val = $config->get('route_middleware_installed');

        return is_null($val) ? self::ROUTE_MIDDLEWARE_INSTALLED : $val;
    }

    /**
     * Get Admin Middleware.
     */
    public static function getAdminMiddleware()
    {
        $config = ConfigHelper::getWriter();
        $val = $config->get('route_middleware_admin');

        return is_null($val) ? self::ROUTE_MIDDLEWARE_ADMIN : $val;
    }

    /**
     * Get General Middleware Groups.
     */
    public static function getGeneralMiddlewareGroups()
    {
        $config = ConfigHelper::getWriter();
        $val = $config->get('route_middleware_groups_general');

        return is_null($val) ? self::ROUTE_MIDDLEWARE_GROUPS_GENERAL : $val;
    }

    /**
     * Get blog main path.
     */
    public static function getBlogMain()
    {
        $config = ConfigHelper::getWriter();
        $val = $config->get('blog_path');

        return is_null($val) ? self::ROUTE_DEFAULT_BLOG_MAIN : $val;
    }

    /**
     * Get blog prefix.
     */
    public static function getBlogPrefix()
    {
        $config = ConfigHelper::getWriter();
        $val = $config->get('blog_prefix');

        return is_null($val) ? self::ROUTE_DEFAULT_BLOG_PREFIX : $val;
    }

    /**
     * Get admin prefix.
     *
     * @param bool $withSlashes Wheather wrapping slashes should be returned.
     * @param int $slashPos Used to determine where slashes must appear. (i.e. 1 => after, -1 => before, other => both sides)
     * @return string
     */
    public static function getAdminPrefix($withSlashes = false, $slashPos = 0)
    {
        $config = ConfigHelper::getWriter();
        $val = $config->get('admin_prefix');
        $prefix = is_null($val) ? self::ROUTE_DEFAULT_ADMIN_PREFIX : $val;

        // add slashes if requested
        if ($withSlashes && (! empty($prefix) && $prefix != '/')) {
            if ($slashPos == -1) {
                $prefix = "/{$prefix}";
            } elseif ($slashPos == 1) {
                $prefix = "{$prefix}/";
            } else {
                $prefix = "/{$prefix}/";
            }
        }

        // remove any duplicate slashes
        $prefix = str_replace('//', '/', $prefix);

        return $prefix;
    }

    /**
     * Get auth prefix.
     * @return string
     */
    public static function getAuthPrefix()
    {
        $config = ConfigHelper::getWriter();
        $val = $config->get('auth_prefix');

        return is_null($val) ? self::ROUTE_DEFAULT_AUTH_PREFIX : $val;
    }

    /**
     * Get password prefix.
     * @return string
     */
    public static function getPasswordPrefix()
    {
        $config = ConfigHelper::getWriter();
        $val = $config->get('password_prefix');

        return is_null($val) ? self::ROUTE_DEFAULT_PASSWORD_PREFIX : $val;
    }

    /**
     * Retrieve a route path. Route without server name etc.
     * @param string $routeName
     * @return string Path
     */
    public static function routePath($routeName)
    {
        $request = resolve('request');

        return preg_replace("/https?:\/\/{$request->server->get('SERVER_NAME')}\//", null, route($routeName));
    }
}
