<?php

namespace App\Admin\Controllers;

use App\User;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
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

            $content->header('Users');
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

            $content->header('User');
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

            $content->header('User');
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
        return Admin::grid(User::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->img('Profile Picture')->display(function ($img) {
                $url= Storage::url($img);
                return "<img src='{$url}' alt='Profile Picture' width='75' height='75' />";
            });
            $grid->name('Name');
            $grid->email('Email');
            $grid->money('Money')->display(function ($money) {
                return $money." SR";
            });
            $grid->projects('No. of Projects')->display(function ($comments) {
                $count = count($comments);
                return "<span class='label label-warning'>{$count}</span>";
            });
            $grid->active('Status')->display(function ($active) {
                if($active == 1){
                    return "<span class='label label-warning'>Active</span>";
                }else{
                    return "<span class='label label-warning'>Inactive</span>";
                }
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
        return Admin::form(User::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->text("name")->rules('required');
            $form->email("email")->rules(function ($form) {

                if (!$id = $form->model()->id) {
                    return 'required|unique:users,email';
                }

            });
            $form->password("password");

            $form->textarea("description")->rules('required');
            $form->image("img", "Image")->rules('nullable');
            $countries= Array ( "Afghanistan" => "Afghanistan", "Albania" => "Albania", "Algeria" => "Algeria", "American Samoa" => "American Samoa", "Andorra" => "Andorra", "Angola" => "Angola", "Anguilla" => "Anguilla", "Antarctica" => "Antarctica", "Antigua and Barbuda" => "Antigua and Barbuda", "Argentina" => "Argentina", "Armenia" => "Armenia", "Aruba" => "Aruba", "Australia" => "Australia", "Austria" => "Austria", "Azerbaijan" => "Azerbaijan", "Bahamas" => "Bahamas", "Bahrain" => "Bahrain", "Bangladesh" => "Bangladesh", "Barbados" => "Barbados", "Belarus" => "Belarus", "Belgium" => "Belgium", "Belize" => "Belize", "Benin" => "Benin", "Bermuda" => "Bermuda", "Bhutan" => "Bhutan", "Bolivia" => "Bolivia", "Bosnia and Herzegowina" => "Bosnia and Herzegowina", "Botswana" => "Botswana", "Bouvet Island" => "Bouvet Island", "Brazil" => "Brazil", "British Indian Ocean Territory" => "British Indian Ocean Territory", "Brunei Darussalam" => "Brunei Darussalam", "Bulgaria" => "Bulgaria", "Burkina Faso" => "Burkina Faso", "Burundi" => "Burundi", "Cambodia" => "Cambodia", "Cameroon" => "Cameroon", "Canada" => "Canada", "Cape Verde" => "Cape Verde", "Cayman Islands" => "Cayman Islands", "Central African Republic" => "Central African Republic", "Chad" => "Chad", "Chile" => "Chile", "China" => "China", "Christmas Island" => "Christmas Island", "Cocos (Keeling) Islands" => "Cocos (Keeling) Islands", "Colombia" => "Colombia", "Comoros" => "Comoros", "Congo" => "Congo", "Congo, the Democratic Republic of the" => "Congo, the Democratic Republic of the", "Cook Islands" => "Cook Islands", "Costa Rica" => "Costa Rica", "Cote d'Ivoire" => "Cote d'Ivoire", "Croatia (Hrvatska)" => "Croatia (Hrvatska)", "Cuba" => "Cuba", "Cyprus" => "Cyprus", "Czech Republic" => "Czech Republic", "Denmark" => "Denmark", "Djibouti" => "Djibouti", "Dominica" => "Dominica", "Dominican Republic" => "Dominican Republic", "East Timor" => "East Timor", "Ecuador" => "Ecuador", "Egypt" => "Egypt", "El Salvador" => "El Salvador", "Equatorial Guinea" => "Equatorial Guinea", "Eritrea" => "Eritrea", "Estonia" => "Estonia", "Ethiopia" => "Ethiopia", "Falkland Islands (Malvinas)" => "Falkland Islands (Malvinas)", "Faroe Islands" => "Faroe Islands", "Fiji" => "Fiji", "Finland" => "Finland", "France" => "France", "France Metropolitan" => "France Metropolitan", "French Guiana" => "French Guiana", "French Polynesia" => "French Polynesia", "French Southern Territories" => "French Southern Territories", "Gabon" => "Gabon", "Gambia" => "Gambia", "Georgia" => "Georgia", "Germany" => "Germany", "Ghana" => "Ghana", "Gibraltar" => "Gibraltar", "Greece" => "Greece", "Greenland" => "Greenland", "Grenada" => "Grenada", "Guadeloupe" => "Guadeloupe", "Guam" => "Guam", "Guatemala" => "Guatemala", "Guinea" => "Guinea", "Guinea-Bissau" => "Guinea-Bissau", "Guyana" => "Guyana", "Haiti" => "Haiti", "Heard and Mc Donald Islands" => "Heard and Mc Donald Islands", "Holy See (Vatican City State)" => "Holy See (Vatican City State)", "Honduras" => "Honduras", "Hong Kong" => "Hong Kong", "Hungary" => "Hungary", "Iceland" => "Iceland", "India" => "India", "Indonesia" => "Indonesia", "Iran (Islamic Republic of)" => "Iran (Islamic Republic of)", "Iraq" => "Iraq", "Ireland" => "Ireland", "Israel" => "Israel", "Italy" => "Italy", "Jamaica" => "Jamaica", "Japan" => "Japan", "Jordan" => "Jordan", "Kazakhstan" => "Kazakhstan", "Kenya" => "Kenya", "Kiribati" => "Kiribati", "Korea, Democratic People's Republic of" => "Korea, Democratic People's Republic of", "Korea, Republic of" => "Korea, Republic of", "Kuwait" => "Kuwait", "Kyrgyzstan" => "Kyrgyzstan", "Lao, People's Democratic Republic" => "Lao, People's Democratic Republic", "Latvia" => "Latvia", "Lebanon" => "Lebanon", "Lesotho" => "Lesotho", "Liberia" => "Liberia", "Libyan Arab Jamahiriya" => "Libyan Arab Jamahiriya", "Liechtenstein" => "Liechtenstein", "Lithuania" => "Lithuania", "Luxembourg" => "Luxembourg", "Macau" => "Macau", "Macedonia, The Former Yugoslav Republic of" => "Macedonia, The Former Yugoslav Republic of", "Madagascar" => "Madagascar", "Malawi" => "Malawi", "Malaysia" => "Malaysia", "Maldives" => "Maldives", "Mali" => "Mali", "Malta" => "Malta", "Marshall Islands" => "Marshall Islands", "Martinique" => "Martinique", "Mauritania" => "Mauritania", "Mauritius" => "Mauritius", "Mayotte" => "Mayotte", "Mexico" => "Mexico", "Micronesia, Federated States of" => "Micronesia, Federated States of", "Moldova, Republic of" => "Moldova, Republic of", "Monaco" => "Monaco", "Mongolia" => "Mongolia", "Montserrat" => "Montserrat", "Morocco" => "Morocco", "Mozambique" => "Mozambique", "Myanmar" => "Myanmar", "Namibia" => "Namibia", "Nauru" => "Nauru", "Nepal" => "Nepal", "Netherlands" => "Netherlands", "Netherlands Antilles" => "Netherlands Antilles", "New Caledonia" => "New Caledonia", "New Zealand" => "New Zealand", "Nicaragua" => "Nicaragua", "Niger" => "Niger", "Nigeria" => "Nigeria", "Niue" => "Niue", "Norfolk Island" => "Norfolk Island", "Northern Mariana Islands" => "Northern Mariana Islands", "Norway" => "Norway", "Oman" => "Oman", "Pakistan" => "Pakistan", "Palau" => "Palau", "Panama" => "Panama", "Papua New Guinea" => "Papua New Guinea", "Paraguay" => "Paraguay", "Peru" => "Peru", "Philippines" => "Philippines", "Pitcairn" => "Pitcairn", "Poland" => "Poland", "Portugal" => "Portugal", "Puerto Rico" => "Puerto Rico", "Qatar" => "Qatar", "Reunion" => "Reunion", "Romania" => "Romania", "Russian Federation" => "Russian Federation", "Rwanda" => "Rwanda", "Saint Kitts and Nevis" => "Saint Kitts and Nevis", "Saint Lucia" => "Saint Lucia", "Saint Vincent and the Grenadines" => "Saint Vincent and the Grenadines", "Samoa" => "Samoa", "San Marino" => "San Marino", "Sao Tome and Principe" => "Sao Tome and Principe", "Saudi Arabia" => "Saudi Arabia", "Senegal" => "Senegal", "Seychelles" => "Seychelles", "Sierra Leone" => "Sierra Leone", "Singapore" => "Singapore", "Slovakia (Slovak Republic)" => "Slovakia (Slovak Republic)", "Slovenia" => "Slovenia", "Solomon Islands" => "Solomon Islands", "Somalia" => "Somalia", "South Africa" => "South Africa", "South Georgia and the South Sandwich Islands" => "South Georgia and the South Sandwich Islands", "Spain" => "Spain", "Sri Lanka" => "Sri Lanka", "St. Helena" => "St. Helena", "St. Pierre and Miquelon" => "St. Pierre and Miquelon", "Sudan" => "Sudan", "Suriname" => "Suriname", "Svalbard and Jan Mayen Islands" => "Svalbard and Jan Mayen Islands", "Swaziland" => "Swaziland", "Sweden" => "Sweden", "Switzerland" => "Switzerland", "Syrian Arab Republic" => "Syrian Arab Republic", "Taiwan, Province of China" => "Taiwan, Province of China", "Tajikistan" => "Tajikistan", "Tanzania, United Republic of" => "Tanzania, United Republic of", "Thailand" => "Thailand", "Togo" => "Togo", "Tokelau" => "Tokelau", "Tonga" => "Tonga", "Trinidad and Tobago" => "Trinidad and Tobago", "Tunisia" => "Tunisia", "Turkey" => "Turkey", "Turkmenistan" => "Turkmenistan", "Turks and Caicos Islands" => "Turks and Caicos Islands", "Tuvalu" => "Tuvalu", "Uganda" => "Uganda", "Ukraine" => "Ukraine", "United Arab Emirates" => "United Arab Emirates", "United Kingdom" => "United Kingdom", "United States" => "United States", "United States Minor Outlying Islands" => "United States Minor Outlying Islands", "Uruguay" => "Uruguay", "Uzbekistan" => "Uzbekistan", "Vanuatu" => "Vanuatu", "Venezuela" => "Venezuela", "Vietnam" => "Vietnam", "Virgin Islands (British)" => "Virgin Islands (British)", "Virgin Islands (U.S.)" => "Virgin Islands (U.S.)", "Wallis and Futuna Islands" => "Wallis and Futuna Islands", "Western Sahara" => "Western Sahara", "Yemen" => "Yemen", "Yugoslavia" => "Yugoslavia", "Zambia" => "Zambia", "Zimbabwe" => "Zimbabwe");
            $form->select("country")->options($countries)->rules('required');
            $form->text("city")->rules('required');
            $form->mobile("phone")->rules('required');
            $form->slider("money")->options(['max' => 1000000000, 'min' => 500000, 'step' => 500000, 'postfix' => ' SR'])->rules('required');
            $investment_types= Array("Short-term" => "Short-term", "Mid-term" => "Mid-term", "Long-term" => "Long-term");
            $form->select("investment_type")->options($investment_types)->rules('required');
            $form->url("linkedin")->rules('nullable');
            $form->url("twitter")->rules('nullable');
            $form->url("facebook")->rules('nullable');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');

            $form->saving(function (Form $form) {

                if($form->model()->password && $form->password == ""){
                    $form->password = $form->model()->password;
                }
                if($form->password && $form->model()->password != $form->password)
                {
                    $form->password = hash::make($form->password);
                }

            });

        });
    }
}
