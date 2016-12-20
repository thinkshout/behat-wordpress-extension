<?php
namespace PaulGibbs\WordpressBehatExtension\Context;

/**
 * Provides step definitions for creating content: post types, comments, and terms.
 */
class ContentContext extends RawWordpressContext
{
    /**
     * Create content of the fiven type.
     *
     * Example: Given there are posts:
     *     | post_type | post_title | post_content | post_status |
     *     | page      | Tes Post   | Hello World  | publish     |
     *
     * @Given /^(?:there are|there is a) posts?:/
     *
     * @param TableNode $posts
     */
    public function thereArePosts(TableNode $posts)
    {
        foreach ($posts->getHash() as $post) {
            $this->createContent($post);
        }
    }
}
