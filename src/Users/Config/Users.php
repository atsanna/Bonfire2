<?php

namespace Bonfire\Users\Config;

use CodeIgniter\Config\BaseConfig;

class Users extends BaseConfig
{
    /**
     * --------------------------------------------------------------------------
     * Gravatar
     * --------------------------------------------------------------------------
     *
     * If true, will attempt to use gravatar.com for users that do not have
     * avatars saved in the system.
     */
    public $useGravatar = false;

    /**
     * --------------------------------------------------------------------------
     * Gravatar Default image
     * --------------------------------------------------------------------------
     *
     * The default image type to use from Gravatar if they do not have an
     * image for that user.
     */
    public $gravatarDefault = 'blank';

    /**
     * --------------------------------------------------------------------------
     * Avatar Display Name
     * --------------------------------------------------------------------------
     *
     * Chooses the basis for the letters shown on avatar when no picture
     * has been uploaded. Valid choices are either 'name' or 'username'.
     */
    public $avatarNameBasis = 'name';

    /**
     * --------------------------------------------------------------------------
     * Avatar Background Colors
     * --------------------------------------------------------------------------
     *
     * The available colors to use for avatar backgrounds.
     */
    public $avatarPalette = [
        '#84705e', '#506da8', '#5b6885', '#7b94b8', '#6c3208',
        '#b97343', '#d6d3ce', '#b392a6', '#af6a76', '#6c6c94',
        '#c38659',
    ];

    /**
     * --------------------------------------------------------------------------
     * Avatar Upload directory
     * --------------------------------------------------------------------------
     * relative to FCPATH and base_url
     */
    public $avatarDirectory = 'uploads/avatars';

    /**
     * --------------------------------------------------------------------------
     * Uploaded Avatar Image Manipulation
     * --------------------------------------------------------------------------
     *
     * Should uploaded avatar be resized? (bool)
     * If so, what is the maximum size (vertical or horizontal, whichever
     * bigger), in px? (int)
     * $avatarResizeFloor is the minimum size of an avatar (set to 32 as required by
     * toolbar avatar size)
     */
    public $avatarResize = false;

    public $avatarSize        = 140;
    public $avatarResizeFloor = 32;

    /**
     * --------------------------------------------------------------------------
     * User Fields
     * --------------------------------------------------------------------------
     * Validation rules used when saving a user.
     */
    public $validation = [
        'id' => [
            // Needed for the id in email and username validation (below);
            // see https://codeigniter4.github.io/userguide/installation/upgrade_435.html
            'rules' => 'permit_empty|is_natural_no_zero',
        ],
        'email' => [
            'label'  => 'Email',
            'rules'  => 'required|valid_email|unique_email[{id}]',
            'errors' => [
                'unique_email' => 'This email is already in use. Could belong to a current or a deleted user.',
            ],
        ],
        'username' => [
            'label' => 'Username', 'rules' => 'required|string|is_unique[users.username,id,{id}]',
        ],
        'first_name' => [
            'label' => 'First Name', 'rules' => 'permit_empty|string|min_length[3]',
        ],
        'last_name' => [
            'label' => 'Last Name', 'rules' => 'permit_empty|string|min_length[3]',
        ],
        'groups.*' => [
            'label' => 'Groups',
            'rules' => 'required',
        ],
    ];

    /**
     * --------------------------------------------------------------------------
     * Additional User Fields
     * --------------------------------------------------------------------------
     *
     * This lists the additional fields that are available on a user's
     * profile page. They are listed first in groups, where the group name is
     * used on the form's fieldset legend.
     *
     * Each field can have the following values in its options array:
     *  - label: the input label. If one is not provided, the field name will be used.
     *  - type: the type of HTML input used. Should be the simpler inputs,
     *      like text, number, email, url, date, etc., as well as textarea, checkbox.
     *      Selects, radios, etc are not supported.
     *  - validation: a validation rules string. If not present will be 'permit_empty|string'
     * NOTE: if you need labels for fields in validation messages, expand the 'validation'
     * value into a multidimensional array of its own, with keys 'label' and 'rules',
     * like it is done with 'baz' value below (adding custom error messages is also possible here).
     *
     * For example:
     *
     * 'Example Fields' => [
     *     'foo' => ['label' => 'Foo', 'type' => 'text', 'validation' => 'permit_empty|string'],
     *     'Bar' => ['type' => 'text', 'validation' => 'required|string'],
     *     'baz' => [
     *         'label' => 'Baz',
     *         'type' => 'checkbox',
     *         'validation' => [
     *             'label' => 'Baz',
     *             'rules' => 'permit_empty|in_list[true,false]'
     *         ],
     *     ],
     * ],
     */
    public $metaFields = [
        'Example Fields' => [
            'blog' => ['label' => 'Blog', 'type' => 'text', 'validation' => 'permit_empty|valid_url_strict'],
        ],
    ];

    /**
     * --------------------------------------------------------------------------
     * Search in additional fields
     * --------------------------------------------------------------------------
     * To enable search in additional user fields, add the field names to this array.
     */
    public $includeMetaFieldsInSearech = [
        'blog',
    ];
}
