<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2010 - 2015 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class TableDataprovider
{
	public static function getTestLoadJoined()
	{
		$db        = JFactory::getDbo();

		// No name escaping, no alias, one unique column, no table name before column
		$jointable      = '#__foftest_foobarjoins ON external_key = foftest_foobar_id';
		$columns        = array('fj_title');
		$config['join'] = $db->getQuery(true)->select($columns)->innerJoin($jointable);

		$data[] = array(
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id', 'config' => $config),
			array('cid' => 2),
			array('return' => true, 'columns' => $columns)
		);

		// No name escaping, no alias, two unique column, no table name before column
		$jointable      = '#__foftest_foobarjoins ON external_key = foftest_foobar_id';
		$columns        = array('fj_title', 'fj_dummy');
		$config['join'] = $db->getQuery(true)->select($columns)->innerJoin($jointable);

		$data[] = array(
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id', 'config' => $config),
			array('cid' => 2),
			array('return' => true, 'columns' => $columns)
		);

		// No name escaping, aliases, two unique column, no table name before column
		$jointable      = '#__foftest_foobarjoins ON external_key = foftest_foobar_id';
		$config['join'] = $db->getQuery(true)->select('fj_title as alias_title, fj_dummy')->innerJoin($jointable);

		$data[] = array(
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id', 'config' => $config),
			array('cid' => 2),
			array('return' => true, 'columns' => array('alias_title', 'fj_dummy'))
		);

		// Select name escaping on columns (not on the aliases), aliases, two unique column, no table name before column
		$jointable      = '#__foftest_foobarjoins ON external_key = foftest_foobar_id';
		$config['join'] = $db->getQuery(true)
							 ->select($db->qn('fj_title').' as alias_title, '.$db->qn('fj_dummy'))
							 ->innerJoin($jointable);

		$data[] = array(
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id', 'config' => $config),
			array('cid' => 2),
			array('return' => true, 'columns' => array('alias_title', 'fj_dummy'))
		);

		// Select name escaping on all columns and tables, aliases, two unique column, no table name before column
		$jointable      = $db->qn('#__foftest_foobarjoins').' ON external_key = foftest_foobar_id';
		$config['join'] = $db->getQuery(true)
							 ->select($db->qn('fj_title').' as '.$db->qn('alias_title').', '.$db->qn('fj_dummy'))
							 ->innerJoin($jointable);

		$data[] = array(
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id', 'config' => $config),
			array('cid' => 2),
			array('return' => true, 'columns' => array('alias_title', 'fj_dummy'))
		);

		// Select name escaping on all columns and tables, table with alias (using AS), aliases, two unique column, no table name before column
		$jointable      = $db->qn('#__foftest_foobarjoins').' AS fjoin_table ON external_key = foftest_foobar_id';
		$config['join'] = $db->getQuery(true)
							 ->select($db->qn('fj_title').' as '.$db->qn('alias_title').', '.$db->qn('fj_dummy'))
							 ->innerJoin($jointable);

		$data[] = array(
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id', 'config' => $config),
			array('cid' => 2),
			array('return' => true, 'columns' => array('alias_title', 'fj_dummy'))
		);

		// Select name escaping on all columns and tables, table with alias (not using AS), aliases, two unique column, no table name before column
		$jointable      = $db->qn('#__foftest_foobarjoins').' fjoin_table ON external_key = foftest_foobar_id';
		$config['join'] = $db->getQuery(true)
							 ->select($db->qn('fj_title').' as '.$db->qn('alias_title').', '.$db->qn('fj_dummy'))
							 ->innerJoin($jointable);

		$data[] = array(
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id', 'config' => $config),
			array('cid' => 2),
			array('return' => true, 'columns' => array('alias_title', 'fj_dummy'))
		);

		// Select name escaping on all columns and tables, aliases, one unique and one non-unique column, table name before column
		$jointable      = $db->qn('#__foftest_foobarjoins').' ON external_key = foftest_foobar_id';
		$config['join'] = $db->getQuery(true)
							 ->select('#__foftest_foobarjoins.'.$db->qn('title').' as '.$db->qn('nonunique_column').', '.$db->qn('fj_dummy'))
							 ->innerJoin($jointable);

		$data[] = array(
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id', 'config' => $config),
			array('cid' => 2),
			array('return' => true, 'columns' => array('nonunique_column', 'fj_dummy'))
		);

		// Select name escaping on all columns and tables (including select ones), aliases,
		// one unique and one non-unique column, table name before column
		$jointable      = $db->qn('#__foftest_foobarjoins').' ON external_key = foftest_foobar_id';
		$config['join'] = $db->getQuery(true)
							 ->select($db->qn('#__foftest_foobarjoins').'.'.$db->qn('title').' as '.$db->qn('nonunique_column').', '.$db->qn('fj_dummy'))
							 ->innerJoin($jointable);

		$data[] = array(
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id', 'config' => $config),
			array('cid' => 2),
			array('return' => true, 'columns' => array('nonunique_column', 'fj_dummy'))
		);

		// Select name escaping on all columns and tables, table with alias (not using AS), aliases,
		// one unique and one non unique column, alias table name before column
		$jointable      = $db->qn('#__foftest_foobarjoins').' AS foojoin ON external_key = foftest_foobar_id';
		$config['join'] = $db->getQuery(true)
			->select($db->qn('foojoin').'.'.$db->qn('title').' as '.$db->qn('nonunique_column').', '.$db->qn('fj_dummy'))
			->innerJoin($jointable);

		$data[] = array(
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id', 'config' => $config),
			array('cid' => 2),
			array('return' => true, 'columns' => array('nonunique_column', 'fj_dummy'))
		);

		// Trying more complex query: left + inner join on the same linked table, with aliases (of course)
		$config['join'] = $db->getQuery(true)
							 ->select($db->qn('a1').'.'.$db->qn('fj_dummy').' as '.$db->qn('first_table_field'))
							 ->select($db->qn('a2').'.'.$db->qn('title').' as '.$db->qn('second_table_field'))
							 ->innerJoin($db->qn('#__foftest_foobarjoins').' AS a1 ON a1.external_key = foftest_foobar_id')
							 ->leftJoin($db->qn('#__foftest_foobarjoins').' AS a2 ON a2.external_key = foftest_foobar_id');

		$data[] = array(
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id', 'config' => $config),
			array('cid' => 2),
			array('return' => true, 'columns' => array('first_table_field', 'second_table_field'))
		);

		return $data;
	}

	public static function getTestBind()
	{
		//TODO Create a dataset with "rules", too

		// Check when onBeforeBind is false
		$data[] = array(false, false, array(), array(), array());

		// Check binding with array
		$data[] = array(true, true, array('title' => 'Binded array title'), array(), array(
			array(
				'field' => 'title',
				'value' => 'Binded array title',
				'msg'   => 'Wrong value binded')
		)
		);

		// Check binding with object
		$bind   = new stdClass();
		$bind->title = 'Binded object title';

		$data[] = array(true, true, $bind, array(), array(
			array(
				'field' => 'title',
				'value' => 'Binded object title',
				'msg'   => 'Wrong value binded')
		)
		);

		// Check binding with array and array ignore fields
		$bind   = new stdClass();
		$bind->title = 'Binded object title';
		$bind->slug  = 'Ignored field';

		$data[] = array(true, true, $bind, array('slug'), array(
			array(
				'field' => 'title',
				'value' => 'Binded object title',
				'msg'   => 'Wrong value binded'),
			array(
				'field' => 'slug',
				'value' => '',
				'msg'   => 'Ignored field binded')
		)
		);

		// Check binding with array and string ignore fields
		$bind              = new stdClass();
		$bind->title       = 'Binded object title';
		$bind->slug        = 'Ignored field';
		$bind->created_by  = 'Ignored field';

		$data[] = array(true, true, $bind, 'slug created_by', array(
			array(
				'field' => 'title',
				'value' => 'Binded object title',
				'msg'   => 'Wrong value binded'),
			array(
				'field' => 'slug',
				'value' => '',
				'msg'   => 'Ignored field binded'),
			array(
				'field' => 'created_by',
				'value' => '',
				'msg'   => 'Ignored field binded')
		)
		);

		return $data;
	}

	public static function getTestStore()
	{
		// Test vs onBefore returns false
		$data[] = array(
			array('before' => false, 'after' => false),
			array('table'  => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array(
				'loadid'      => 3,
				'alias'       => '',
				'assetkey'    => 'com_foftest.foobar',
				'bind'        => array('title' => 'Modified title', 'enabled' => 0),
				'nullable'    => '',
				'updateNulls' => false
			),
			array('return' => false, 'more' => false)
		);

		// Test vs onAfter returns false
		$data[] = array(
			array('before' => true, 'after' => false),
			array('table'  => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array(
				'loadid'      => 3,
				'alias'       => '',
				'assetkey'    => 'com_foftest.foobar',
				'bind'        => array('title' => 'Modified title', 'enabled' => 0),
				'nullable'    => '',
				'updateNulls' => false
			),
			array('return' => false, 'more' => false)
		);

		// Update test with assets, without updating nulls
		$data[] = array(
			array('before' => true, 'after' => true),
			array('table'  => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array(
				'loadid'      => 3,
				'alias'       => '',
				'assetkey'    => 'com_foftest.foobar',
				'bind'        => array('title' => 'Modified title', 'enabled' => 0),
				'nullable'    => array('created_by' => null),
				'updateNulls' => false
			),
			array('return' => true, 'more' => true)
		);

		// Update test with assets, updating nulls
		$data[] = array(
			array('before' => true, 'after' => true),
			array('table'  => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array(
				'loadid'      => 3,
				'alias'       => '',
				'assetkey'    => 'com_foftest.foobar',
				'bind'        => array('title' => 'Modified title', 'enabled' => 0),
				'nullable'    => array('created_by' => null),
				'updateNulls' => true
			),
			array('return' => true, 'more' => true)
		);

		// Update test without assets
		$data[] = array(
			array('before' => true, 'after' => true),
			array('table'  => 'jos_foftest_bares', 'id' => 'foftest_bare_id'),
			array(
				'loadid'      => 3,
				'alias'       => '',
				'assetkey'    => '',
				'bind'        => array('title'=> 'Modified title'),
				'nullable'    => '',
				'updateNulls' => false
			),
			array('return' => true, 'more' => true)
		);

		// Insert new object with assets, updating nulls
		$data[] = array(
			array('before' => true, 'after' => true),
			array('table'  => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array(
				'loadid'   => '',
				'alias'    => '',
				'assetkey' => 'com_foftest.foobar',
				'bind'     => array('title' => 'New element', 'enabled' => 0),
				'nullable' => array('created_by' => null),
				'updateNulls' => true
			),
			array('return' => true, 'more' => true)
		);

		// Insert new object with assets, without updating nulls
		$data[] = array(
			array('before' => true, 'after' => true),
			array('table'  => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array(
				'loadid'      => '',
				'alias'       => '',
				'assetkey'    => 'com_foftest.foobar',
				'bind'        => array('title' => 'New element', 'enabled' => 0),
				'nullable'    => array('created_by' => null),
				'updateNulls' => false
			),
			array('return' => true, 'more' => true)
		);

		// Update test with assets and alias
		$data[] = array(
			array('before' => true, 'after' => true),
			array('table'  => 'jos_foftest_foobaraliases', 'id' => 'id_foobar_aliases'),
			array(
				'loadid'      => 3,
				'alias'       => 'fo_asset_id',
				'assetkey'    => '',
				'bind'        => array('fo_title' => 'Modified title', 'fo_enabled' => 0),
				'nullable'    => '',
				'updateNulls' => false
			),
			array('return' => true, 'more' => true)
		);

		return $data;
	}

	public static function getTestMove()
	{
		// Test vs table not loaded
		$data[] = array(
			array('before' => true, 'after' => true),
			array('table'  => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array('id'     => 0, 'delta'  => 1, 'where' => ''),
			array('return' => false)
		);

		// Test vs onBeforeMove returns false
		$data[] = array(
			array('before' => false, 'after' => false),
			array('table'  => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array('id'     => 4, 'delta'  => 0, 'where' => ''),
			array('return' => false)
		);

		// Test vs delta = 0 and onAfterMove returns false
		$data[] = array(
			array('before' => true, 'after' => false),
			array('table'  => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array('id'     => 4, 'delta'  => 0, 'where' => ''),
			array('return' => false)
		);

		// Test vs delta = 0 and onAfterMove returns true
		$data[] = array(
			array('before' => true, 'after' => true),
			array('table'  => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array('id'     => 4, 'delta'  => 0, 'where' => ''),
			array('return' => true, 'more' => false)
		);

		// Test vs delta = 1 (everything else ok) inner record
		$data[] = array(
			array('before' => true, 'after' => true),
			array('table'  => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array('id'     => 4, 'delta'  => 1, 'where' => ''),
			array(
				'return' => true,
				'more'   => true,
				'value'  => 5,
				'msg'    => 'Move() wrong ordering with delta = 1, no where',
				'find'   => array(
					'id'    => 5,
					'value' => 4,
					'msg'   => 'Move() wrong record swapping with delta = 1, no where'
				)
			)
		);

		// Test vs delta = 1 (everything else ok) outer record
		$data[] = array(
			array('before' => true, 'after' => true),
			array('table'  => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array('id'     => 5, 'delta'  => 1, 'where' => ''),
			array(
				'return' => true,
				'more'   => true,
				'value'  => 5,
				'msg'    => 'Move() wrong ordering with delta = 1, no where'
			)
		);

		// Test vs delta = -1 (everything else ok) inner record
		$data[] = array(
			array('before' => true, 'after' => true),
			array('table'  => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array('id'     => 4, 'delta'  => -1, 'where' => ''),
			array(
				'return' => true,
				'more'   => true,
				'value'  => 3,
				'msg'    => 'Move() wrong ordering with delta = -1, no where',
				'find'   => array(
					'id'    => 3,
					'value' => 4,
					'msg'   => 'Move() wrong record swapping with delta = -1, no where'
				)

			)
		);

		// Test vs delta = -1 (everything else ok) outer record
		$data[] = array(
			array('before' => true, 'after' => true),
			array('table'  => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array('id'     => 1, 'delta'  => -1, 'where' => ''),
			array(
				'return' => true,
				'more'   => true,
				'value'  => 1,
				'msg'    => 'Move() wrong ordering with delta = -1, no where'
			)
		);

		// Test vs delta = 1 and where (everything else ok), inner record
		$data[] = array(
			array('before' => true, 'after' => true),
			array('table'  => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array('id'     => 2, 'delta'  => 1, 'where' => 'enabled = 0'),
			array(
				'return' => true,
				'more'   => true,
				'value'  => 4,
				'msg'    => 'Move() wrong ordering with delta = 1, where enabled = 0',
				'find'   => array(
					'id'    => 4,
					'value' => 2,
					'msg'   => 'Move() wrong record swapping with delta = 1, where enabled = 0'
				)
			)
		);

		// Test vs delta = 1 and where (everything else ok), outer record
		$data[] = array(
			array('before' => true, 'after' => true),
			array('table'  => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array('id'     => 4, 'delta'  => 1, 'where' => 'enabled = 0'),
			array(
				'return' => true,
				'more'   => true,
				'value'  => 4,
				'msg'    => 'Move() wrong ordering with delta = 1, where enabled = 0',
			)
		);

		// Test vs delta = -1 and where (everything else ok), outer record
		$data[] = array(
			array('before' => true, 'after' => true),
			array('table'  => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array('id'     => 2, 'delta'  => -1, 'where' => 'enabled = 0'),
			array(
				'return' => true,
				'more'   => true,
				'value'  => 2,
				'msg'    => 'Move() wrong ordering with delta = -1, where enabled = 0',
			)
		);

		// Test vs delta = -1 and where (everything else ok), inner record
		$data[] = array(
			array('before' => true, 'after' => true),
			array('table'  => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array('id'     => 4, 'delta'  => -1, 'where' => 'enabled = 0'),
			array(
				'return' => true,
				'more'   => true,
				'value'  => 2,
				'msg'    => 'Move() wrong ordering with delta = 1, where enabled = 0',
				'find'   => array(
					'id'    => 2,
					'value' => 4,
					'msg'   => 'Move() wrong record swapping with delta = 1, where enabled = 0'
				)
			)
		);

		// Test vs delta = 1, using aliases
		$data[] = array(
			array('before' => true, 'after' => true),
			array('table'  => 'jos_foftest_foobaraliases', 'id' => 'id_foobar_aliases'),
			array('id'     => 4, 'alias' => 'fo_ordering', 'delta'  => 1, 'where' => ''),
			array(
				'return' => true,
				'more'   => true,
				'value'  => 5,
				'msg'    => 'Move() wrong ordering with delta = 1, no where',
				'find'   => array(
					'id'    => 5,
					'value' => 4,
					'msg'   => 'Move() wrong record swapping with delta = 1, no where'
				)
			)
		);

		return $data;
	}

	public static function getTestReorder()
	{
		// Test vs onBeforeReorder returns false
		$data[] = array(
			array('before' => false, 'after' => false),
			array('table'  => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array('id' => '', 'ordering' => '', 'where' => ''),
			array('return' => false)
		);

		// Test vs reorder, positive number, no where
		$data[] = array(
			array('before' => true, 'after' => true),
			array('table'  => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array('id' => 3, 'ordering' => 100, 'where' => ''),
			array(
				'return' => true,
				'more'   => true,
				'msg'    => 'Reorder() wrong reordered recordset with positive number, no where',
				'list'   => array(
					array('foftest_foobar_id' => 1, 'ordering' => 1),
					array('foftest_foobar_id' => 2, 'ordering' => 2),
					array('foftest_foobar_id' => 4, 'ordering' => 3),
					array('foftest_foobar_id' => 5, 'ordering' => 4),
					array('foftest_foobar_id' => 3, 'ordering' => 5)
				)
			)
		);

		// Test vs reorder, negative number, no where
		$data[] = array(
			array('before' => true, 'after' => true),
			array('table'  => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array('id' => 3, 'ordering' => -100, 'where' => ''),
			array(
				'return' => true,
				'more'   => true,
				'msg'    => 'Reorder() wrong reordered recordset with negative number, no where',
				'list'   => array(
					array('foftest_foobar_id' => 3, 'ordering' => -100),
					array('foftest_foobar_id' => 1, 'ordering' => 1),
					array('foftest_foobar_id' => 2, 'ordering' => 2),
					array('foftest_foobar_id' => 4, 'ordering' => 3),
					array('foftest_foobar_id' => 5, 'ordering' => 4)
				)
			)
		);

		// Test vs reorder, positive number, where enabled = 1
		$data[] = array(
			array('before' => true, 'after' => true),
			array('table'  => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array('id' => 3, 'ordering' => 100, 'where' => 'enabled = 1'),
			array(
				'return' => true,
				'more'   => true,
				'msg'    => 'Reorder() wrong reordered recordset with positive number, where enabled = 1',
				'list'   => array(
					array('foftest_foobar_id' => 1, 'ordering' => 1),
					array('foftest_foobar_id' => 5, 'ordering' => 2),
					array('foftest_foobar_id' => 3, 'ordering' => 3)
				)
			)
		);

		// Test vs reorder, negative number, where enabled = 1
		$data[] = array(
			array('before' => true, 'after' => true),
			array('table'  => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array('id' => 3, 'ordering' => -100, 'where' => 'enabled = 1'),
			array(
				'return' => true,
				'more'   => true,
				'msg'    => 'Reorder() wrong reordered recordset with negative number, where enabled = 1',
				'list'   => array(
					array('foftest_foobar_id' => 3, 'ordering' => -100),
					array('foftest_foobar_id' => 1, 'ordering' => 1),
					array('foftest_foobar_id' => 5, 'ordering' => 2)
				)
			)
		);

		// Test vs aliased reorder, positive number, no where
		$data[] = array(
			array('before' => true, 'after' => true),
			array('table'  => 'jos_foftest_foobaraliases', 'id' => 'id_foobar_aliases'),
			array('id' => 3, 'ordering' => 100, 'alias' => 'fo_ordering', 'where' => ''),
			array(
				'return' => true,
				'more'   => true,
				'msg'    => 'Reorder() wrong aliased reordered recordset with positive number, no where',
				'list'   => array(
					array('id_foobar_aliases' => 1, 'fo_ordering' => 1),
					array('id_foobar_aliases' => 2, 'fo_ordering' => 2),
					array('id_foobar_aliases' => 4, 'fo_ordering' => 3),
					array('id_foobar_aliases' => 5, 'fo_ordering' => 4),
					array('id_foobar_aliases' => 3, 'fo_ordering' => 5)
				)
			)
		);

		return $data;
	}

	public static function getTestCheckout()
	{
		// Test vs table without checkout support
		$data[] = array(
			array('table' => 'jos_foftest_bares', 'id' => 'foftest_bare_id'),
			array(
				'loadid' => '',
				'user'   => 99,
				'id'     => 5,
				'alias'  => ''
			),
			array(
				'return' => true,
				'more'   => false
			)
		);

		// Test vs table, no id given
		$data[] = array(
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array(
				'loadid' => '',
				'user'   => 99,
				'id'     => null,
				'alias'  => ''
			),
			array(
				'return' => false,
				'more'   => false
			)
		);

		// Test vs table, id given
		$data[] = array(
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array(
				'loadid' => '',
				'user'   => 99,
				'id'     => 4,
				'alias'  => ''
			),
			array(
				'return' => true,
				'more'   => true
			)
		);

		// Test vs table, no id given, load it first
		$data[] = array(
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array(
				'loadid' => 4,
				'user'   => 99,
				'id'     => null,
				'alias'  => ''
			),
			array(
				'return' => true,
				'more'   => true
			)
		);

		// Test vs aliased table, no id given, load it first
		$data[] = array(
			array('table' => 'jos_foftest_foobaraliases', 'id' => 'id_foobar_aliases'),
			array(
				'loadid' => 4,
				'user'   => 99,
				'id'     => null,
				'alias'  => array(
					'lockby' => 'fo_locked_by',
					'lockon' => 'fo_locked_on'
				)
			),
			array(
				'return' => true,
				'more'   => true
			)
		);

		return $data;
	}

	public static function getTestCheckin()
	{
		// Test vs table without checkin support
		$data[] = array(
			array('table' => 'jos_foftest_bares', 'id' => 'foftest_bare_id'),
			array(
				'loadid' => '',
				'id'     => 5,
				'alias'  => ''
			),
			array(
				'return' => true,
				'more'   => false
			)
		);

		// Test vs table, no id given
		$data[] = array(
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array(
				'loadid' => '',
				'id'     => null,
				'alias'  => ''
			),
			array(
				'return' => false,
				'more'   => false
			)
		);

		// Test vs table, id given
		$data[] = array(
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array(
				'loadid' => '',
				'id'     => 4,
				'alias'  => ''
			),
			array(
				'return' => true,
				'more'   => true
			)
		);

		// Test vs table, no id given, load it first
		$data[] = array(
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array(
				'loadid' => 4,
				'id'     => null,
				'alias'  => ''
			),
			array(
				'return' => true,
				'more'   => true
			)
		);

		// Test vs aliased table, no id given, load it first
		$data[] = array(
			array('table' => 'jos_foftest_foobaraliases', 'id' => 'id_foobar_aliases'),
			array(
				'loadid' => 4,
				'id'     => null,
				'alias'  => array(
					'lockby' => 'fo_locked_by',
					'lockon' => 'fo_locked_on'
				)
			),
			array(
				'return' => true,
				'more'   => true
			)
		);

		return $data;
	}

	public static function getTestIsCheckedOut()
	{
		// Unlocked record, no user
		$data[] = array(
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array(
				'id'        => 4,
				'alias'     => '',
				'with'      => ''
			),
			array(
				'return'    => false,
				'msg'       => 'isCheckedOut: Wrong return value, unlocked record with no user'
			)
		);

		// Unlocked record, with user
		$data[] = array(
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array(
				'id'        => 4,
				'alias'     => '',
				'with'      => 42
			),
			array(
				'return'    => false,
				'msg'       => 'isCheckedOut: Wrong return value, unlocked record with user'
			)
		);

		// Locked record, without user
		$data[] = array(
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array(
				'id'        => 5,
				'alias'     => '',
				'with'      => ''
			),
			array(
				'return'    => true,
				'msg'       => 'isCheckedOut: Wrong return value, locked record without user'
			)
		);

		// Locked record, with user
		$data[] = array(
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array(
				'id'        => 5,
				'alias'     => '',
				'with'      => 42
			),
			array(
				'return'    => true,
				'msg'       => 'isCheckedOut: Wrong return value, locked record with user'
			)
		);

		// Locked record, without user
		$data[] = array(
			array('table' => 'jos_foftest_foobaraliases', 'id' => 'id_foobar_aliases'),
			array(
				'id'        => 5,
				'alias'     => array(
					'lockon'  => 'fo_locked_on',
					'lockby'  => 'fo_locked_by'
				),
				'with'      => 42
			),
			array(
				'return'    => true,
				'msg'       => 'isCheckedOut: Wrong return value, locked record with user'
			)
		);

		return $data;
	}

	public static function getTestCopy()
	{
		// Test with onBefore returning false
		$data[] = array(
			array('before' => false, 'after' => true),
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array('alias'  => '', 'loadid' => '', 'cids' => 1),
			array('return' => true,	'more' => true,	'cids' => array(1 => 0))
		);

		// Test with no ids
		$data[] = array(
			array('before' => true, 'after' => true),
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array('alias'  => '','loadid' => '','cids' => ''),
			array('return' => false, 'more' => false)
		);

		// Single record, loading it first
		$data[] = array(
			array('before' => true, 'after' => true),
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array('alias'  => '', 'loadid' => 1, 'cids' => ''),
			array('return' => true, 'more' => true, 'cids' => array(1 => 6))
		);

		// Single record, passing it to the copy function
		$data[] = array(
			array('before' => true, 'after' => true),
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array('alias'  => '', 'loadid' => '', 'cids' => 2),
			array('return' => true,	'more' => true,	'cids' => array(2 => 6))
		);

		// Single record, checked out (so it shold be skipped)
		$data[] = array(
			array('before' => true, 'after' => true),
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array('alias'  => '', 'loadid' => '', 'cids' => 5),
			array('return' => true, 'more' => true,	'cids' => array(5 => 0))
		);

		// Multiple records, some of them shouldn't be copied
		$data[] = array(
			array('before' => true, 'after' => true),
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array(
				'alias'  => '',
				'loadid' => '',
				'cids'   => array(1,3,4,5)
			),
			array(
				'return' => true,
				'more'   => true,
				'cids'   => array(
					1 => 6,
					3 => 7,
					4 => 8,
					5 => 0,
				)
			)
		);

		// Test vs bare table (no special columns)
		$data[] = array(
			array('before' => true, 'after' => true),
			array('table' => 'jos_foftest_bares', 'id' => 'foftest_bare_id'),
			array(
				'alias'  => '',
				'loadid' => '',
				'cids'   => array(1,2,3)
			),
			array(
				'return' => true,
				'more'   => true,
				'cids'   => array(
					1 => 4,
					2 => 5,
					3 => 6
				)
			)
		);

		// Test vs table with aliases
		$data[] = array(
			array('before' => true, 'after' => true),
			array('table' => 'jos_foftest_foobaraliases', 'id' => 'id_foobar_aliases'),
			array(
				'alias'  => array(
					'slug'        => 'fo_slug',
					'title'       => 'fo_title',
					'created_by'  => 'fo_created_by',
					'created_on'  => 'fo_created_on',
					'modified_by' => 'fo_modified_by',
					'modified_on' => 'fo_modified_on',
					'locked_by'   => 'fo_locked_by',
					'locked_on'   => 'fo_locked_on'
				),
				'loadid' => '',
				'cids'   => array(1,2,5)
			),
			array(
				'return' => true,
				'more'   => true,
				'cids'   => array(
					1 => 6,
					2 => 7,
					5 => 0
				)
			)
		);

		return $data;
	}

	public static function getTestPublish()
	{
		// Test with onBefore returning false
		$data[] = array(
			array('before' => false),
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array('alias'  => '', 'loadid' => '', 'cids' => 1, 'publish' => 1, 'user' => ''),
			array('return' => false, 'more' => false, 'cids' => array())
		);

		// Test with single id already published
		$data[] = array(
			array('before' => true),
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array('alias'  => '', 'loadid' => '', 'cids' => 1, 'publish' => 1, 'user' => ''),
			array('return' => true,	'more' => true, 'cids' => array(1 => 1))
		);

		// Test with table loaded
		$data[] = array(
			array('before' => true),
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array('alias'  => '', 'loadid' => 2, 'cids' => '', 'publish' => 1, 'user' => ''),
			array('return' => true,	'more' => true, 'cids' => array(2 => 1))
		);

		// Test with array of elements - publish them all
		$data[] = array(
			array('before' => true),
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array('alias'  => '', 'loadid' => '', 'cids' => array(2,3), 'publish' => 1, 'user' => ''),
			array('return' => true,	'more' => true, 'cids' => array(2 => 1, 3 => 1))
		);

		// Test with array of elements - some are locked, but we don't provide an userid
		$data[] = array(
			array('before' => true),
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array('alias'  => '', 'loadid' => '', 'cids' => array(4,5), 'publish' => 0, 'user' => ''),
			array('return' => true,	'more' => true, 'cids' => array(4 => 0, 5 => 1))
		);

		// Test with array of elements - some are locked, we provide a valid userid
		$data[] = array(
			array('before' => true),
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array('alias'  => '', 'loadid' => '', 'cids' => array(4,5), 'publish' => 0, 'user' => 99),
			array('return' => true,	'more' => true, 'cids' => array(4 => 0, 5 => 0))
		);

		// Test with array of elements - some are locked, we provide an invalid userid
		$data[] = array(
			array('before' => true),
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array('alias'  => '', 'loadid' => '', 'cids' => array(4,5), 'publish' => 0, 'user' => 88),
			array('return' => true,	'more' => true, 'cids' => array(4 => 0, 5 => 1))
		);

		// Test with table with no publish field
		$data[] = array(
			array('before' => true),
			array('table' => 'jos_foftest_bares', 'id' => 'foftest_bare_id'),
			array('alias'  => '', 'loadid' => '', 'cids' => '', 'publish' => '', 'user' => ''),
			array('return' => false, 'more' => false, 'cids' => array())
		);

		// Test with aliased table
		$data[] = array(
			array('before' => true),
			array('table' => 'jos_foftest_foobaraliases', 'id' => 'id_foobar_aliases'),
			array('alias'  => array(
					'enabled'   => 'fo_enabled',
					'locked_by' => 'fo_locked_by'),
			      'loadid' => '',
			      'cids' => array(4,5),
			      'publish' => 0,
			      'user' => 88),
			array('return' => true,	'more' => true, 'cids' => array(4 => 0, 5 => 1))
		);

		return $data;
	}

	/**
	 * Dataprovideer for testDelete test. Please note that data dealing with assets has been removed, since now we're
	 * handling that using behaviours, so the right place test that is when we test behaviors, not the table
	 *
	 * @return array
	 */
	public static function getTestDelete()
	{
		// Test when onBefore returns false
		$data[] = array(
			array('onBeforeDelete' => false, 'onAfterDelete' => true),
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array('loadid' => 4, 'cid' => ''),
			array('return' => false, 'more' => true, 'count' => 1)
		);

		// Test with successful delete with asset
		$data[] = array(
			array('onBeforeDelete' => true, 'onAfterDelete' => true),
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array('loadid' => 4, 'cid' => '', 'assetkey' => 'com_foftest.foobar'),
			array('return' => true, 'more' => true, 'count' => 0)
		);

		// Test with with delete vs empty asset_id
		$data[] = array(
			array('onBeforeDelete' => true, 'onAfterDelete' => true),
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array('loadid' => 2, 'cid' => '', 'assetkey' => 'com_foftest.foobar'),
			array('return' => true, 'more' => true, 'count' => 0)
		);

		// Test with successful delete, passing the table id
		$data[] = array(
			array('onBeforeDelete' => true, 'onAfterDelete' => true),
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array('loadid' => '', 'cid' => 4, 'mockAsset' => false, 'assetkey' => 'com_foftest.foobar'),
			array('return' => true, 'more' => true, 'count' => 0, 'checkAsset' => true)
		);

		// Test with successful delete, but onAfter returns false
		$data[] = array(
			array('onBeforeDelete' => true, 'onAfterDelete' => false),
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array('loadid' => 4, 'cid' => '', 'assetkey' => 'com_foftest.foobar'),
			array('return' => false, 'more' => true, 'count' => 0)
		);

		// Test with successful delete vs bare table
		$data[] = array(
			array('onBeforeDelete' => true, 'onAfterDelete' => true),
			array('table' => 'jos_foftest_bares', 'id' => 'foftest_bare_id'),
			array('loadid' => 2, 'cid' => ''),
			array('return' => true, 'more' => true, 'count' => 0)
		);

		// Test vs bare table
		$data[] = array(
			array('onBeforeDelete' => true, 'onAfterDelete' => true),
			array('table' => 'jos_foftest_bares', 'id' => 'foftest_bare_id'),
			array('loadid' => 2, 'cid' => ''),
			array('return' => true, 'more' => true, 'count' => 0)
		);

		// Test vs table with alias
		$data[] = array(
			array('onBeforeDelete' => true, 'onAfterDelete' => true),
			array('table' => 'jos_foftest_foobaraliases', 'id' => 'id_foobar_aliases'),
			array('loadid' => 2, 'cid' => '', 'alias' => array('asset_id' => 'fo_asset_id')),
			array('return' => true, 'more' => true, 'count' => 0)
		);

		return $data;
	}

	public static function getTestHit()
	{
		// With no hits support
		$data[] = array(
			array('onBeforeHit' => true, 'onAfterHit' => true),
			array('table' => 'jos_foftest_bares', 'id' => 'foftest_bare_id'),
			array('loadid' => 1, 'cid' => ''),
			array('return' => true)
		);

		// With no hits support - onBefore returns false
		$data[] = array(
			array('onBeforeHit' => false, 'onAfterHit' => true),
			array('table' => 'jos_foftest_bares', 'id' => 'foftest_bare_id'),
			array('loadid' => 1, 'cid' => ''),
			array('return' => false)
		);

		// With hits support - table not loaded
		$data[] = array(
			array('onBeforeHit' => true, 'onAfterHit' => true),
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array('loadid' => '', 'cid' => ''),
			array('return' => false)
		);

		// With hits support - onBefore returns true
		$data[] = array(
			array('onBeforeHit' => true, 'onAfterHit' => true),
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array('loadid' => 2, 'cid' => ''),
			array('return' => true, 'hits' => 24)
		);

		// With hits support - onBefore returns true, using id
		$data[] = array(
			array('onBeforeHit' => true, 'onAfterHit' => true),
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array('loadid' => '', 'cid' => 2),
			array('return' => true, 'hits' => 24)
		);

		// With hits support - onAfter returns false
		$data[] = array(
			array('onBeforeHit' => true, 'onAfterHit' => false),
			array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
			array('loadid' => 2, 'cid' => ''),
			array('return' => false, 'hits' => 24)
		);

		// With hits support - with table alias - everything ok
		$data[] = array(
			array('onBeforeHit' => true, 'onAfterHit' => true),
			array('table' => 'jos_foftest_foobaraliases', 'id' => 'id_foobar_aliases'),
			array('loadid' => 2, 'cid' => '', 'alias' => array('hits' => 'fo_hits')),
			array('return' => true, 'hits' => 13)
		);

		return $data;
	}

	public static function getTestToCSV()
	{
		$data[] = array(
			array('table' => 'foobars'),
			array('loadid' => '', 'separator' => ','),
			array('string' => '"","","","","","","0","1","","","","","",""')
		);

		$data[] = array(
			array('table' => 'foobars'),
			array('loadid' => 2, 'separator' => ','),
			array('string' => '"2","Second row","second-row","0","2","23","0","1","42","0000-00-00 00:00:00","0","0000-00-00 00:00:00","0","0000-00-00 00:00:00"')
		);

		$data[] = array(
			array('table' => 'foobars'),
			array('loadid' => 2, 'separator' => '\t'),
			array('string' => '"2"\t"Second row"\t"second-row"\t"0"\t"2"\t"23"\t"0"\t"1"\t"42"\t"0000-00-00 00:00:00"\t"0"\t"0000-00-00 00:00:00"\t"0"\t"0000-00-00 00:00:00"')
		);

		return $data;
	}

	public static function getTestGetData()
	{
		$data[] = array(
			array('table' => 'foobars'),
			array('loadid' => ''),
			array('return' => array(
				'foftest_foobar_id' => null,
				'title'             => null,
				'slug'              => null,
				'enabled'           => null,
				'ordering'          => null,
				'hits'              => null,
				'asset_id'          => 0,
				'access'            => 1,
				'created_by'        => null,
				'created_on'        => null,
				'modified_by'       => null,
				'modified_on'       => null,
				'locked_by'         => null,
				'locked_on'         => null
			))
		);

		$data[] = array(
			array('table' => 'foobars'),
			array('loadid' => '2'),
			array('return' => array(
				'foftest_foobar_id' => 2,
				'title'             => 'Second row',
				'slug'              => 'second-row',
				'enabled'           => 0,
				'ordering'          => 2,
				'hits'              => 23,
				'asset_id'          => 0,
				'access'            => 1,
				'created_by'        => 42,
				'created_on'        => '0000-00-00 00:00:00',
				'modified_by'       => 0,
				'modified_on'       => '0000-00-00 00:00:00',
				'locked_by'         => 0,
				'locked_on'         => '0000-00-00 00:00:00'
			))
		);

		return $data;
	}

	public static function getCSVHeader()
	{
		$data[] = array(
			array('table' => 'foobars'),
			array('loadid' => '', 'separator' => ','),
			array('string' => '"foftest_foobar_id","title","slug","enabled","ordering","hits","asset_id","access","created_by","created_on","modified_by","modified_on","locked_by","locked_on"')
		);

		$data[] = array(
			array('table' => 'foobars'),
			array('loadid' => 2, 'separator' => '\t'),
			array('string' => '"foftest_foobar_id"\t"title"\t"slug"\t"enabled"\t"ordering"\t"hits"\t"asset_id"\t"access"\t"created_by"\t"created_on"\t"modified_by"\t"modified_on"\t"locked_by"\t"locked_on"')
		);

		return $data;
	}

	public static function getTableFields()
	{
		// Standard behavior
		$data[] = array(
			array('table' => 'foobars'),
			array('use_table_cache' => true),
			array('fields' => array(
				'foftest_foobar_id', 'title', 'slug', 'enabled', 'ordering', 'hits', 'asset_id', 'access', 'created_by',
				'created_on', 'modified_by', 'modified_on',	'locked_by', 'locked_on')
			)
		);

		// Using cache but nuking the internal table one
		$data[] = array(
			array('table' => 'foobars'),
			array('use_table_cache' => true, 'tableCache' => array()),
			array('fields' => array(
				'foftest_foobar_id', 'title', 'slug', 'enabled', 'ordering', 'hits', 'asset_id', 'access', 'created_by',
				'created_on', 'modified_by', 'modified_on',	'locked_by', 'locked_on')
			)
		);

		// Using internal cache but nuking the internal table and field ones
		$data[] = array(
			array('table' => 'foobars'),
			array('use_table_cache' => true, 'tableCache' => array(), 'tableFieldCache' => array()),
			array('fields' => array(
				'foftest_foobar_id', 'title', 'slug', 'enabled', 'ordering', 'hits', 'asset_id', 'access', 'created_by',
				'created_on', 'modified_by', 'modified_on',	'locked_by', 'locked_on')
			)
		);

		// Joomla Cache ON and internal (empty) cache ON
		$data[] = array(
			array('table' => 'foobars'),
			array('use_table_cache' => true, 'tableCache' => array(), 'tableFieldCache' => array(), 'joomlaCache' => true),
			array('fields' => array(
				'foftest_foobar_id', 'title', 'slug', 'enabled', 'ordering', 'hits', 'asset_id', 'access', 'created_by',
				'created_on', 'modified_by', 'modified_on',	'locked_by', 'locked_on')
			)
		);

        // Joomla Cache OFF and internal (empty) cache ON
        $data[] = array(
            array('table' => 'foobars'),
            array('use_table_cache' => true, 'tableCache' => array(), 'tableFieldCache' => array(), 'joomlaCache' => false),
            array('fields' => array(
                'foftest_foobar_id', 'title', 'slug', 'enabled', 'ordering', 'hits', 'asset_id', 'access', 'created_by',
                'created_on', 'modified_by', 'modified_on',	'locked_by', 'locked_on')
            )
        );

		// Joomla Cache ON and internal (empty) cache OFF
		$data[] = array(
			array('table' => 'foobars'),
			array('use_table_cache' => false, 'tableCache' => array(), 'tableFieldCache' => array(), 'joomlaCache' => true),
			array('fields' => array(
				'foftest_foobar_id', 'title', 'slug', 'enabled', 'ordering', 'hits', 'asset_id', 'access', 'created_by',
				'created_on', 'modified_by', 'modified_on',	'locked_by', 'locked_on')
			)
		);

        // Joomla Cache OFF and internal (empty) cache OFF
        $data[] = array(
            array('table' => 'foobars'),
            array('use_table_cache' => false, 'tableCache' => array(), 'tableFieldCache' => array(), 'joomlaCache' => false),
            array('fields' => array(
                'foftest_foobar_id', 'title', 'slug', 'enabled', 'ordering', 'hits', 'asset_id', 'access', 'created_by',
                'created_on', 'modified_by', 'modified_on',	'locked_by', 'locked_on')
            )
        );

		// Joomla Cache ON and internal (empty) cache OFF - supplying (existing) tablename (#__ format)
		$data[] = array(
			array('table' => 'foobars'),
			array('use_table_cache' => false, 'tableCache' => array(), 'tableFieldCache' => array(), 'joomlaCache' => true, 'table' => '#__foftest_bares'),
			array('fields' => array(
					'foftest_bare_id', 'title'
				)
			)
		);

		// Joomla Cache ON and internal (empty) cache OFF - supplying (existing) tablename (plain format)
		$data[] = array(
			array('table' => 'foobars'),
			array('use_table_cache' => false, 'tableCache' => array(), 'tableFieldCache' => array(), 'joomlaCache' => true, 'table' => 'jos_foftest_bares'),
			array('fields' => array(
					'foftest_bare_id', 'title'
				)
			)
		);

        // Joomla Cache OFF and internal (empty) cache OFF - supplying (existing) tablename (plain format)
        $data[] = array(
            array('table' => 'foobars'),
            array('use_table_cache' => false, 'tableCache' => array(), 'tableFieldCache' => array(), 'joomlaCache' => false, 'table' => 'jos_foftest_bares'),
            array('fields' => array(
                'foftest_bare_id', 'title'
            )
            )
        );

		// Joomla Cache ON and internal (empty) cache OFF - supplying (NON existing) tablename (#__ format)
		$data[] = array(
			array('table' => 'foobars'),
			array('use_table_cache' => true, 'tableCache' => array(), 'tableFieldCache' => array(), 'joomlaCache' => true, 'table' => '#__wrong'),
			array('fields' => false)
		);

        // Joomla Cache OFF and internal (empty) cache OFF - supplying (NON existing) tablename (#__ format)
        $data[] = array(
            array('table' => 'foobars'),
            array('use_table_cache' => true, 'tableCache' => array(), 'tableFieldCache' => array(), 'joomlaCache' => false, 'table' => '#__wrong'),
            array('fields' => false)
        );

		return $data;
	}

	public static function getIsQuoted()
	{
		$db = JFactory::getDbo();

		$data[] = array(
			array('table' => 'foobars'),
			array('column' => ''),
			array('return' => false)
		);

		$data[] = array(
			array('table' => 'foobars'),
			array('column' => 'enabled'),
			array('return' => false)
		);

		$data[] = array(
			array('table' => 'foobars'),
			array('column' => '92'),
			array('return' => false)
		);

		$data[] = array(
			array('table' => 'foobars'),
			array('column' => '__test'),
			array('return' => false)
		);

		$data[] = array(
			array('table' => 'foobars'),
			array('column' => '#__tablename'),
			array('return' => false)
		);

		$data[] = array(
			array('table' => 'foobars'),
			array('column' => $db->qn('#__tablename')),
			array('return' => true)
		);

		return $data;
	}

    public static function getTestOnBeforeStore()
    {
        // New record
        $data[] = array(
            array('table' => 'foobars'),
            array(
                'updateNulls' => false,
                'bind'        => array(
                    'title' => 'F0FTest title'
                )
            ),
            array(
                'return' => true,
                'fields' => array(
                    'foftest_foobar_id' => null,
                    'title'             => 'F0FTest title',
                    'slug'              => 'f0ftest-title',
                    'enabled'           => '',
                    'ordering'          => '',
                    'hits'              => '',
                    'asset_id'          => 0,
                    'access'            => 1,
                    'created_by'        => 42,
                    'created_on'        => 'NOT NULL',
                    'modified_by'       => '',
                    'modified_on'       => '',
                    'locked_by'         => '',
                    'locked_on'         => '',

                )
            )
        );

        // New record - invalid slug
        $data[] = array(
            array('table' => 'foobars'),
            array(
                'updateNulls' => false,
                'bind'        => array(
                    'title' => 'F0FTest title',
                    'slug'  => 'foftest.. .title'
                )
            ),
            array(
                'return' => true,
                'fields' => array(
                    'foftest_foobar_id' => null,
                    'title'             => 'F0FTest title',
                    'slug'              => 'foftest-title',
                    'enabled'           => '',
                    'ordering'          => '',
                    'hits'              => '',
                    'asset_id'          => 0,
                    'access'            => 1,
                    'created_by'        => 42,
                    'created_on'        => 'NOT NULL',
                    'modified_by'       => '',
                    'modified_on'       => '',
                    'locked_by'         => '',
                    'locked_on'         => '',

                )
            )
        );

        // Editing a record, slug already in use by the same record
        $data[] = array(
            array('table' => 'foobars'),
            array(
                'updateNulls' => false,
                'bind'        => array(
                    'foftest_foobar_id' => 3,
                    'title'         => 'Third row',
                    'created_by'    => 22,
                    'created_on'    => '2013-12-06 13:00:00'
                )
            ),
            array(
                'return' => true,
                'fields' => array(
                    'foftest_foobar_id' => 3,
                    'title'             => 'Third row',
                    'slug'              => 'third-row',
                    'enabled'           => '',
                    'ordering'          => '',
                    'hits'              => '',
                    'asset_id'          => 0,
                    'access'            => 1,
                    'created_by'        => 22,
                    'created_on'        => '2013-12-06 13:00:00',
                    'modified_by'       => 42,
                    'modified_on'       => 'NOT NULL',
                    'locked_by'         => '',
                    'locked_on'         => '',

                )
            )
        );


        // New record, slug already in use
        $data[] = array(
            array('table' => 'foobars'),
            array(
                'updateNulls' => false,
                'bind'        => array(
                    'foftest_foobar_id' => null,
                    'title'         => 'Third row',
                    'created_by'    => 22,
                    'created_on'    => '2013-12-06 13:00:00'
                )
            ),
            array(
                'return' => true,
                'fields' => array(
                    'foftest_foobar_id' => null,
                    'title'             => 'Third row',
                    'slug'              => 'third-row-1',
                    'enabled'           => '',
                    'ordering'          => '',
                    'hits'              => '',
                    'asset_id'          => 0,
                    'access'            => 1,
                    'created_by'        => 22,
                    'created_on'        => '2013-12-06 13:00:00',
                    'modified_by'       => 42,
                    'modified_on'       => 'NOT NULL',
                    'locked_by'         => '',
                    'locked_on'         => '',

                )
            )
        );

        // Editing a record, slug already in use by the same record - TABLE WITH ALIASES
        $data[] = array(
            array('table' => 'foobaraliases'),
            array(
                'updateNulls' => false,
                'tbl_key'     => 'id_foobar_aliases',
                'alias'       => array(
                    'title'             => 'fo_title',
                    'slug'              => 'fo_slug',
                    'enabled'           => 'fo_enabled',
                    'ordering'          => 'fo_ordering',
                    'hits'              => 'fo_hits',
                    'asset_id'          => 'fo_asset_id',
                    'created_by'        => 'fo_created_by',
                    'created_on'        => 'fo_created_on',
                    'modified_by'       => 'fo_modified_by',
                    'modified_on'       => 'fo_modified_on',
                    'locked_by'         => 'fo_locked_by',
                    'locked_on'         =>   'fo_locked_on'
                ),
                'bind'        => array(
                    'id_foobar_aliases' => 3,
                    'fo_title'          => 'Third row',
                    'fo_created_by'        => 22,
                    'fo_created_on'    => '2013-12-06 13:00:00'
                )
            ),
            array(
                'return' => true,
                'fields' => array(
                    'id_foobar_aliases' => 3,
                    'fo_title'             => 'Third row',
                    'fo_slug'              => 'third-row',
                    'fo_enabled'           => '',
                    'fo_ordering'          => '',
                    'fo_hits'              => '',
                    'fo_asset_id'          => 0,
                    'fo_access'            => 0,
                    'fo_created_by'        => 22,
                    'fo_created_on'        => '2013-12-06 13:00:00',
                    'fo_modified_by'       => 42,
                    'fo_modified_on'       => 'NOT NULL',
                    'fo_locked_by'         => '',
                    'fo_locked_on'         => '',

                )
            )
        );

        // New bare record (no special fields)
        $data[] = array(
            array('table' => 'bares'),
            array(
                'updateNulls' => false,
                'bind'        => array(
                    'title' => 'F0FTest title'
                )
            ),
            array(
                'return' => true,
                'fields' => array(
                    'foftest_bare_id' => null,
                    'title' => 'F0FTest title',
                )
            )
        );

        return $data;
    }

    public static function getTestGetAssetName()
    {
        $data[] = array(
            array('table' => 'foobars'),
            array('loadid' => 2),
            array('assetName' => 'com_foftest.foobar.2')
        );

        $data[] = array(
            array('table' => 'foobaraliases'),
            array(
                'loadid'  => 2,
                'tbl_key' => 'id_foobar_aliases',
                'alias'   => array(
                    'asset_id' => 'fo_asset_id'
                )),
            array('assetName' => 'com_foftest.foobaralias.2')
        );

        $data[] = array(
            array('table' => 'bares'),
            array('loadid'  => 2),
            array('assetName' => 'com_foftest.bare.2')
        );

        return $data;
    }

	public static function getTestGetContentType()
	{
		$data[] = array('com_foftest', 'foobar', 'com_foftest.foobar', 'Wrong content type');
		$data[] = array('com_foftest', 'foobars', 'com_foftest.foobar', 'Wrong content type');

		return $data;
	}
}