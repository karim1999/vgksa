<?php

namespace App\Admin\Controllers;

use App\Category;
use App\Project;

use App\Tag;
use App\User;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('Projects');
            $content->description('All');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('Project');
            $content->description('Edit');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('Project');
            $content->description('New');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Project::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->img('Picture')->display(function ($img) {
                $url= Storage::url($img);
                return "<img src='{$url}' alt='Profile Picture' width='75' height='75' />";
            });
            $grid->title("Title");
            $grid->amount('Money Needed')->display(function ($money) {
                return "<span class='label label-warning'>{$money} SR</span>";
            });
            $grid->user()->name("Owner");
            $grid->category()->name("category");
            $grid->tags("Tags")->display(function ($tags) {

                $tags = array_map(function ($tag) {
                    return "<span class='label label-success'>{$tag['name']}</span>";
                }, $tags);

                return join('&nbsp;', $tags);
            });

            $grid->created_at();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Project::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->text("title")->rules('required');
            $form->textarea("description")->rules('required');
            $form->image("img", "Image")->rules('required');
            $form->slider("amount" , "Money needed")->options(['max' => 1000000000, 'min' => 500000, 'step' => 500000, 'postfix' => ' SR'])->rules('required');
            $form->select("user_id", "Owner")->options(User::all()->pluck('name', 'id'));
            $form->select("category_id", "Category")->options(Category::all()->pluck('name', 'id'));
            $form->multipleSelect('tags')->options(Tag::all()->pluck('name', 'id'));
            $form->file("presentation")->rules('nullable');
            $form->file("study", "Report")->rules('nullable');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
