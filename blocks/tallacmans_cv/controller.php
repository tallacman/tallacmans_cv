<?php namespace Concrete\Package\TallacmansCv\Block\TallacmansCv;

defined("C5_EXECUTE") or die("Access Denied.");

use AssetList;
use Concrete\Core\Block\BlockController;
use Concrete\Core\Editor\LinkAbstractor;
use Core;
use Database;

class Controller extends BlockController
{
    public $btFieldsRequired = ['cv' => []];
    protected $btExportTables = ['btTallacmansCv', 'btTallacmansCvCvEntries'];
    protected $btTable = 'btTallacmansCv';
    protected $btInterfaceWidth = 550;
    protected $btInterfaceHeight = 700;
    protected $btIgnorePageThemeGridFrameworkContainer = false;
    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputOnPost = true;
    protected $btCacheBlockOutputForRegisteredUsers = true;
    protected $btDefaultSet = 'multimedia';
    protected $pkg = 'tallacmans_cv';

    public function getBlockTypeDescription()
    {
        return t("Tallacmans Curriculum Vitae");
    }

    public function getBlockTypeName()
    {
        return t("Tallacmans CV");
    }

    public function getSearchableContent()
    {
        $content = [];
        $db = Database::connection();
        $cv_items = $db->fetchAll('SELECT * FROM btTallacmansCvCvEntries WHERE bID = ? ORDER BY sortOrder', [$this->bID]);
        foreach ($cv_items as $cv_item_k => $cv_item_v) {
            if (isset($cv_item_v["position"]) && trim($cv_item_v["position"]) != "") {
                $content[] = $cv_item_v["position"];
            }
            if (isset($cv_item_v["employer"]) && trim($cv_item_v["employer"]) != "") {
                $content[] = $cv_item_v["employer"];
            }
            if (isset($cv_item_v["summary"]) && trim($cv_item_v["summary"]) != "") {
                $content[] = $cv_item_v["summary"];
            }
        }
        return implode(" ", $content);
    }

    public function view()
    {
        $db = Database::connection();
        $cv = [];
        $cv_items = $db->fetchAll('SELECT * FROM btTallacmansCvCvEntries WHERE bID = ? ORDER BY sortOrder', [$this->bID]);
        foreach ($cv_items as $cv_item_k => &$cv_item_v) {
            $cv_item_v["summary"] = isset($cv_item_v["summary"]) ? LinkAbstractor::translateFrom($cv_item_v["summary"]) : null;
        }
        $this->set('cv_items', $cv_items);
        $this->set('cv', $cv);
    }

    public function delete()
    {
        $db = Database::connection();
        $db->delete('btTallacmansCvCvEntries', ['bID' => $this->bID]);
        parent::delete();
    }

    public function duplicate($newBID)
    {
        $db = Database::connection();
        $cv_items = $db->fetchAll('SELECT * FROM btTallacmansCvCvEntries WHERE bID = ? ORDER BY sortOrder', [$this->bID]);
        foreach ($cv_items as $cv_item) {
            unset($cv_item['id']);
            $cv_item['bID'] = $newBID;
            $db->insert('btTallacmansCvCvEntries', $cv_item);
        }
        parent::duplicate($newBID);
    }

    public function add()
    {
        $this->addEdit();
        $cv = $this->get('cv');
        $this->set('cv_items', []);
        $this->set('cv', $cv);
    }

    public function edit()
    {
        $db = Database::connection();
        $this->addEdit();
        $cv = $this->get('cv');
        $cv_items = $db->fetchAll('SELECT * FROM btTallacmansCvCvEntries WHERE bID = ? ORDER BY sortOrder', [$this->bID]);

        foreach ($cv_items as &$cv_item) {
            $cv_item['summary'] = isset($cv_item['summary']) ? LinkAbstractor::translateFromEditMode($cv_item['summary']) : null;
        }
        $this->set('cv', $cv);
        $this->set('cv_items', $cv_items);
    }

    protected function addEdit()
    {
        $cv = [];
        $this->set('cv', $cv);
        $this->set('identifier', new \Concrete\Core\Utility\Service\Identifier());
        $al = AssetList::getInstance();
        $al->register('css', 'repeatable-ft.form', 'blocks/tallacmans_cv/css_form/repeatable-ft.form.css', [], $this->pkg);
        $al->register('javascript', 'handlebars', 'blocks/tallacmans_cv/js_form/handlebars-v4.0.4.js', [], $this->pkg);
        $al->register('javascript', 'handlebars-helpers', 'blocks/tallacmans_cv/js_form/handlebars-helpers.js', [], $this->pkg);
        $al->register('css', 'datetimepicker', 'blocks/tallacmans_cv/css_form/bootstrap-datetimepicker.min.css', [], $this->pkg);
        $al->register('css', 'bootstrap_fonts', 'blocks/tallacmans_cv/css_form/bootstrap.fonts.css', [], $this->pkg);
        $al->register('css', 'datetimepicker-composer', 'blocks/tallacmans_cv/css_form/bootstrap-datetimepicker-composer.css', [], $this->pkg);
        $al->register('javascript', 'moment', 'blocks/tallacmans_cv/js_form/moment.js', [], $this->pkg);
        $al->register('javascript', 'bootstrap', 'blocks/tallacmans_cv/js_form/bootstrap.min.js', [], $this->pkg);
        $al->register('javascript', 'datetimepicker', 'blocks/tallacmans_cv/js_form/bootstrap-datetimepicker.min.js', [], $this->pkg);
        $this->requireAsset('core/sitemap');
        $this->requireAsset('css', 'repeatable-ft.form');
        $this->requireAsset('javascript', 'handlebars');
        $this->requireAsset('javascript', 'handlebars-helpers');
        $this->requireAsset('redactor');
        $this->requireAsset('core/file-manager');
        $this->requireAsset('css', 'datetimepicker');
        $this->requireAsset('css', 'bootstrap_fonts');
        $this->requireAsset('css', 'datetimepicker-composer');
        $this->requireAsset('javascript', 'moment');
        $this->requireAsset('javascript', 'bootstrap');
        $this->requireAsset('javascript', 'datetimepicker');
        $this->set('btFieldsRequired', $this->btFieldsRequired);
        $this->set('identifier_getString', Core::make('helper/validation/identifier')->getString(18));
    }

    public function save($args)
    {
        $db = Database::connection();
        $rows = $db->fetchAll('SELECT * FROM btTallacmansCvCvEntries WHERE bID = ? ORDER BY sortOrder', [$this->bID]);
        $cv_items = isset($args['cv']) && is_array($args['cv']) ? $args['cv'] : [];
        $queries = [];
        if (!empty($cv_items)) {
            $i = 0;
            foreach ($cv_items as $cv_item) {
                $data = [
                    'sortOrder' => $i + 1,
                ];
                if (isset($cv_item['position']) && trim($cv_item['position']) != '') {
                    $data['position'] = trim($cv_item['position']);
                } else {
                    $data['position'] = null;
                }
                if (isset($cv_item['employer']) && trim($cv_item['employer']) != '') {
                    $data['employer'] = trim($cv_item['employer']);
                } else {
                    $data['employer'] = null;
                }
                $data['summary'] = isset($cv_item['summary']) ? LinkAbstractor::translateTo($cv_item['summary']) : null;
                if (isset($cv_item['dateStart']) && trim($cv_item['dateStart']) != '') {
                    $data['dateStart'] = strtotime(substr($cv_item['dateStart'], 0, 22));
                } else {
                    $data['dateStart'] = null;
                }
                if (isset($cv_item['dateEnd']) && trim($cv_item['dateEnd']) != '') {
                    $data['dateEnd'] = strtotime(substr($cv_item['dateEnd'], 0, 22));
                } else {
                    $data['dateEnd'] = null;
                }
                if (isset($rows[$i])) {
                    $queries['update'][$rows[$i]['id']] = $data;
                    unset($rows[$i]);
                } else {
                    $data['bID'] = $this->bID;
                    $queries['insert'][] = $data;
                }
                $i++;
            }
        }
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $queries['delete'][] = $row['id'];
            }
        }
        if (!empty($queries)) {
            foreach ($queries as $type => $values) {
                if (!empty($values)) {
                    switch ($type) {
                        case 'update':
                            foreach ($values as $id => $data) {
                                $db->update('btTallacmansCvCvEntries', $data, ['id' => $id]);
                            }
                            break;
                        case 'insert':
                            foreach ($values as $data) {
                                $db->insert('btTallacmansCvCvEntries', $data);
                            }
                            break;
                        case 'delete':
                            foreach ($values as $value) {
                                $db->delete('btTallacmansCvCvEntries', ['id' => $value]);
                            }
                            break;
                    }
                }
            }
        }
        parent::save($args);
    }

    public function validate($args)
    {
        $e = Core::make("helper/validation/error");
        $cvEntriesMin = 0;
        $cvEntriesMax = 0;
        $cvEntriesErrors = 0;
        $cv = [];
        if (isset($args['cv']) && is_array($args['cv']) && !empty($args['cv'])) {
            if ($cvEntriesMin >= 1 && count($args['cv']) < $cvEntriesMin) {
                $e->add(t("The %s field requires at least %s entries, %s entered.", t("Curriculum Vitae"), $cvEntriesMin, count($args['cv'])));
                $cvEntriesErrors++;
            }
            if ($cvEntriesMax >= 1 && count($args['cv']) > $cvEntriesMax) {
                $e->add(t("The %s field is set to a maximum of %s entries, %s entered.", t("Curriculum Vitae"), $cvEntriesMax, count($args['cv'])));
                $cvEntriesErrors++;
            }
            if ($cvEntriesErrors == 0) {
                foreach ($args['cv'] as $cv_k => $cv_v) {
                    if (is_array($cv_v)) {
                        if (in_array("position", $this->btFieldsRequired['cv']) && (!isset($cv_v['position']) || trim($cv_v['position']) == "")) {
                            $e->add(t("The %s field is required (%s, row #%s).", t("Postition"), t("Curriculum Vitae"), $cv_k));
                        }
                        if (in_array("employer", $this->btFieldsRequired['cv']) && (!isset($cv_v['employer']) || trim($cv_v['employer']) == "")) {
                            $e->add(t("The %s field is required (%s, row #%s).", t("Employer"), t("Curriculum Vitae"), $cv_k));
                        }
                        if (in_array("summary", $this->btFieldsRequired['cv']) && (!isset($cv_v['summary']) || trim($cv_v['summary']) == "")) {
                            $e->add(t("The %s field is required (%s, row #%s).", t("Summary"), t("Curriculum Vitae"), $cv_k));
                        }
                        if (in_array("dateStart", $this->btFieldsRequired['cv']) && (!isset($cv_v['dateStart']) || trim($cv_v['dateStart']) == "")) {
                            $e->add(t("The %s field is required (%s, row #%s).", t("Start Date"), t("Curriculum Vitae"), $cv_k));
                        } elseif (isset($cv_v['dateStart']) && trim($cv_v['dateStart']) != "" && strtotime($cv_v['dateStart']) <= 0) {
                            $e->add(t("The %s field is not a valid date (%s, row #%s).", t("Start Date"), t("Curriculum Vitae"), $cv_k));
                        }
                        if (in_array("dateEnd", $this->btFieldsRequired['cv']) && (!isset($cv_v['dateEnd']) || trim($cv_v['dateEnd']) == "")) {
                            $e->add(t("The %s field is required (%s, row #%s).", t("End Date"), t("Curriculum Vitae"), $cv_k));
                        } elseif (isset($cv_v['dateEnd']) && trim($cv_v['dateEnd']) != "" && strtotime($cv_v['dateEnd']) <= 0) {
                            $e->add(t("The %s field is not a valid date (%s, row #%s).", t("End Date"), t("Curriculum Vitae"), $cv_k));
                        }
                    } else {
                        $e->add(t("The values for the %s field, row #%s, are incomplete.", t('Curriculum Vitae'), $cv_k));
                    }
                }
            }
        } else {
            if ($cvEntriesMin >= 1) {
                $e->add(t("The %s field requires at least %s entries, none entered.", t("Curriculum Vitae"), $cvEntriesMin));
            }
        }
        return $e;
    }

    public function composer()
    {
        $al = AssetList::getInstance();
        $al->register('css', 'datetimepicker-composer', 'blocks/tallacmans_cv/css_form/bootstrap-datetimepicker-composer.css', [], $this->pkg);
        $al->register('javascript', 'auto-js-' . $this->btHandle, 'blocks/' . $this->btHandle . '/auto.js', [], $this->pkg);
        $this->requireAsset('css', 'datetimepicker-composer');
        $this->requireAsset('javascript', 'auto-js-' . $this->btHandle);
        $this->edit();
    }
}
