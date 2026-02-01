<?php

namespace BitApps\Assist\HTTP\Controllers;

use AllowDynamicProperties;
use BitApps\Assist\Deps\BitApps\WPKit\Http\Request\Request;
use WP_Query;

#[AllowDynamicProperties]
final class WpSearchController
{
    public function wpSearch(Request $request)
    {
        $validated = $request->validate([
            'search'      => ['string', 'sanitize:text'],
            'page'        => ['integer'],
            'postTypes.*' => ['nullable', 'string', 'sanitize:text'],
        ]);

        $postTypes = $validated['postTypes'] ?? ['page', 'post'];

        if (empty($postTypes)) {
            return ['data' => [], 'pagination' => []];
        }

        return $this->getPageAndPosts($validated['search'], $validated['page'], $postTypes);
    }

    private function getPageAndPosts($search, $page, $postTypes)
    {
        $paged = max(1, intval($page));
        $search = trim($search);

        $queryArgs = [
            'post_type'              => $postTypes,
            'post_status'            => 'publish',
            'posts_per_page'         => 10,
            'orderby'                => 'relevance',
            'paged'                  => $paged,
            'no_found_rows'          => false, // keep pagination totals
            'has_password'           => false, // exclude password-protected posts
            'update_post_meta_cache' => false, // performance flag
            'update_post_term_cache' => false, // performance flag
            'ignore_sticky_posts'    => true,  // performance/consistency flag
        ];

        if (!empty($search)) {
            $queryArgs['s'] = $search;
            $queryArgs['search_columns'] = ['post_title'];
        }

        $query = new WP_Query($queryArgs);

        if (is_wp_error($query)) {
            return ['data' => [], 'pagination' => $this->getEmptyPagination()];
        }

        return [
            'data'       => $this->processPosts($query->posts),
            'pagination' => $this->buildPagination($query, $paged)
        ];
    }

    private function processPosts($posts)
    {
        return array_map(function ($post) {
            return [
                'post_link'  => get_permalink($post->ID),
                'post_title' => $post->post_title,
                'post_type'  => $post->post_type,
            ];
        }, array_filter($posts, function ($post) {
            return $post && is_object($post);
        }));
    }

    private function buildPagination($query, $currentPage)
    {
        $maxPages = $query->max_num_pages;

        if ($maxPages <= 0) {
            return $this->getEmptyPagination();
        }

        $nextPage = $currentPage + 1;
        $previousPage = $currentPage - 1;

        return [
            'total'        => $maxPages,
            'current'      => $currentPage,
            'next'         => $nextPage,
            'previous'     => $previousPage,
            'has_next'     => $nextPage <= $maxPages,
            'has_previous' => $previousPage >= 1,
            'total_posts'  => $query->found_posts,
        ];
    }

    private function getEmptyPagination()
    {
        return [
            'total'        => 0,
            'current'      => 1,
            'next'         => null,
            'previous'     => null,
            'has_next'     => false,
            'has_previous' => false,
            'total_posts'  => 0,
        ];
    }
}
