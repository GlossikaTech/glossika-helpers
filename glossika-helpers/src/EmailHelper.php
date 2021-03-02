<?php
/**
 * Created by PhpStorm.
 * User: ray
 * Date: 2021-03-02
 * Time: 16:30
 */

namespace Glossika\GlossikaHelpers;


class EmailHelper
{
    public function __construct() {}

    public function sendEmailViaMendrill(
        $user,
        $email_key,
        $template_name,
        $template_content,
        $sent_by,
        $subject_param = [],
        $to_email = null,
        $from_email = null)
    {
        $template_content = [
            [
                'name' => 'htmlAbove',
                'content' => $this->html,
            ],
            [
                'name' => 'terms',
                'content' => __('email.terms'),
            ],
            [
                'name' => 'privacy',
                'content' => __('email.privacy'),
            ],
            [
                'name' => 'htmlBelow',
                'content' => EmailHelper::unsubscribe_link($this->user->email, $this->email_key),
            ]
        ];

        $message = [
            'subject' => 'STAGING' . 'Glossika Helper TEST',
            'from_email' => $from_email,
            'from_name' => config('mail.from.name'),
            'to' => [
                [
                    'email' => $to_email,
                    'name' => $user->name,
                    'type' => 'to'
                ]
            ],

            'headers' => [
                'Reply-To' => isset($user->specific_reply_to) ? $user->specific_reply_to : config('mail.from.address')
            ],

            'global_merge_vars' => $template_content,

            'tags' => [
                'saas-' . $email_key
            ],
        ];

        $result = \MandrillMail::messages()->sendTemplate(
            $template_name,
            $template_content,
            $message,
            false,
            'Main Pool'
        );

        return 'ok';
    }
}