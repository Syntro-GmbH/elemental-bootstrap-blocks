<?php
namespace Syntro\ElementalBootstrapBlocks\Element;

use SilverStripe\Forms\OptionsetField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\ORM\DataList;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\ListboxField;
use SilverStripe\Assets\Image;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Blog\Model\BlogCategory;
use SilverStripe\Blog\Model\BlogTag;
use SilverStripe\Blog\Model\BlogPost;
use DNADesign\Elemental\Models\BaseElement;
use UncleCheese\DisplayLogic\Forms\Wrapper;
use Syntro\ElementalBootstrapBlocks\Extension\SplitRatioExtension;

/**
 * Adds an image block
 *
 * @author Matthias Leutenegger
 */
class BlogPostReelBlock extends BaseElement
{
    /**
     * Defines the database table name
     * @config
     *  @var string
     */
    private static $table_name = 'BlockBlogPostReel';

    /**
     * Singular name for CMS
     * @config
     *  @var string
     */
    private static $singular_name = 'Blog Posts';

    /**
     * Plural name for CMS
     * @config
     *  @var string
     */
    private static $plural_name = 'Blog Posts';

    /**
     * @config
     * @var bool
     */
    private static $inline_editable = false;

    /**
     * Display a show title button
     *
     * @config
     * @var boolean
     */
    private static $displays_title_in_template = false;

    /**
     * @config
     * @var string
     */
    private static $icon = 'font-icon-block-bookmark';

    /**
     * available styles
     * @config
     * @var array
     */
    private static $styles = [];

    /**
     * Database fields
     * @config
     * @var array
     */
    private static $db = [
        'SelectPosts' => 'Enum("latest,specific","latest")',
        'ShowLatest' => 'Int',
    ];

    /**
     * Add default values to database
     * @config
     *  @var array
     */
    private static $defaults = [
        'SelectPosts' => 'latest',
        'ShowLatest' =>  5,
    ];

    /**
     * Many_many relationship - This is done via config to allow for the blog
     * module to be not installed
     * @var array
     */
    // private static $many_many = [
    //     'Categories' => BlogCategory::class,
    //     'Tags' => BlogTag::class,
    //     'Posts' => BlogPost::class,
    // ];

    /**
     * CMS Fields
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName([
            'Categories',
            'Tags',
            'Posts',
            'SelectPosts',
        ]);

        $fields->addFieldToTab(
            'Root.Main',
            OptionsetField::create(
                'SelectPosts',
                _t(__CLASS__ . '.SELECTPOSTSTITLE', 'Show'),
                [
                    'latest' => _t(__CLASS__ . '.SELECTPOSTSLATESTTITLE', 'Latest Posts'),
                    'specific' => _t(__CLASS__ . '.SELECTPOSTSSPECIFICTITLE', 'Specific Posts'),
                ]
            ),
            'ShowLatest'
        );

        // Add Categories & Tags filter
        if (ClassInfo::exists(BlogCategory::class) && ClassInfo::exists(BlogTag::class)) {
            $fields->addFieldsToTab(
                'Root.Main',
                [
                    $categoriesField = ListboxField::create(
                        'Categories',
                        _t(__CLASS__ . '.CATEGORIESTITLE', 'Filter by Categories'),
                        BlogCategory::get(),
                        $this->Categories(),
                    ),
                    $tagsField = ListboxField::create(
                        'Tags',
                        _t(__CLASS__ . '.TAGSTITLE', 'Filter by Tags'),
                        BlogTag::get(),
                        $this->Tags(),
                    )
                ]
            );
            $categoriesField->hideUnless('SelectPosts')->isEqualTo('latest');
            $tagsField->hideUnless('SelectPosts')->isEqualTo('latest');
            /** @var NumericField */
            $numField = $fields->fieldByName('Root.Main.ShowLatest');
            $numField->hideUnless('SelectPosts')->isEqualTo('latest');
            $numField->setTitle(_t(__CLASS__ . '.SHOWLATESTTITLE', 'Show latest X Posts'));


            // Add Specific posts fields
            $postsField = GridField::create(
                'Posts',
                _t(__CLASS__ . '.POSTSTITLE', 'Posts'),
                $this->Posts(),
                $postsConfig = GridFieldConfig_RelationEditor::create()
            );
            $postsConfig->removeComponentsByType(GridFieldAddNewButton::class);
            $fields->addFieldsToTab(
                'Root.Main',
                [
                    $postsTitle = Wrapper::create(HeaderField::create(
                        'titleSpecific',
                        _t(__CLASS__ . '.POSTSHEADERTITLE', 'Posts Shown in the Reel')
                    )),
                    $postsWrapper = Wrapper::create($postsField)
                ]
            );
            $postsTitle->hideUnless('SelectPosts')->isEqualTo('specific');
            $postsWrapper->hideUnless('SelectPosts')->isEqualTo('specific');
        }



        return $fields;
    }

    /**
     * getType
     *
     * @return string
     */
    public function getType()
    {
        return _t(__CLASS__ . '.BlockType', 'Blog Posts Reel');
    }


    /**
     * getPostsInReel - fetch the posts to be shown in the ree
     *
     * @return DataList
     */
    public function getPostsInReel()
    {
        switch ($this->SelectPosts) {
            case 'specific':
                return $this->Posts();
            case 'latest':
                $posts = BlogPost::get();
                if ($this->Tags()->count() > 0) {
                    $posts = $posts->filter('Tags.ID', $this->Tags()->map('ID', 'ID')->keys());
                }
                if ($this->Categories()->count() > 0) {
                    $posts = $posts->filter('Categories.ID', $this->Categories()->map('ID', 'ID')->keys());
                }
                return $posts->limit($this->ShowLatest);
            default:
                return BlogPost::get()->filter('ID', 0);
        }
    }
}
