<?php

namespace MagicApp\AppDto\ResponseDto;

use MagicObject\MagicObject;

/**
 * Class ListDataDto
 *
 * Represents the data structure for a table, including column titles and row.
 * This class manages the titles of columns, a data map, and the row of data 
 * represented as RowDto instances. It provides methods for appending 
 * titles, data maps, and row, as well as resetting these structures.
 * 
 * The class extends the ToString base class, enabling string representation based on 
 * the specified property naming strategy.
 * 
 * @package MagicApp\AppDto\ResponseDto
 * @author Kamshory
 * @link https://github.com/Planetbiru/MagicApp
 */
class ListDataDto extends ToString
{
    /**
     * An array of column titles for the data table.
     *
     * @var ListDataTitleDto[]
     */
    protected $title;

    /**
     * An array of data maps for the data table.
     *
     * @var DataMap[]
     */
    protected $dataMap;

    /**
     * The name of the primary key in the data structure.
     *
     * @var string[]|null
     */
    protected $primaryKeyName;

    /**
     * An associative array mapping primary key names to their data types.
     *
     * @var string[]
     */
    protected $primaryKeyDataType;

    /**
     * Current page
     *
     * @var PageDto
     */
    protected $page;

    /**
     * An array of row, each represented as a RowDto.
     *
     * @var RowDto[]
     */
    protected $row;
    
    /**
     * Data control
     *
     * @var ButtonFormData[]
     */
    protected $dataControl;
    
    /**
     * Initializes the object and sets up the necessary properties.
     * This constructor initializes the `row` property as an empty array and 
     * the `dataControl` property as an empty array.
     */
    public function __construct()
    {
        $this->row = [];
        $this->dataControl = [];
    }

    /**
     * Get the name of the primary key in the data structure.
     *
     * @return string[]|null
     */ 
    public function getPrimaryKeyName()
    {
        return $this->primaryKeyName;
    }

    /**
     * Set the name of the primary key in the data structure.
     *
     * @param string[]|null $primaryKeyName The name of the primary key.
     * @return self Returns the current instance for method chaining.
     */ 
    public function setPrimaryKeyName($primaryKeyName)
    {
        $this->primaryKeyName = $primaryKeyName;
        return $this;
    }
    
    /**
     * Add a primary key name and its data type to the list of primary keys.
     *
     * This method initializes the primary key name and data type properties as arrays if they haven't been set,
     * then appends the new primary key name and its corresponding data type to the lists.
     *
     * @param string $primaryKeyName The primary key name to add.
     * @param string $primaryKeyDataType The primary key data type to add.
     * @return self The instance of this class for method chaining.
     */
    public function addPrimaryKeyName($primaryKeyName, $primaryKeyDataType)
    {
        if (!isset($this->primaryKeyName)) {
            $this->primaryKeyName = array(); // Initialize as an array if not set
            $this->primaryKeyDataType = array(); // Initialize as an array if not set
        }   
        $this->primaryKeyName[] = $primaryKeyName; // Append the primary key name
        $this->primaryKeyDataType[] = new NameValueDto($primaryKeyName, $primaryKeyDataType); // Append the primary key data type
        return $this; // Returns the current instance for method chaining.
    }
    
    /**
     * Append a column title to the table.
     *
     * @param ListDataTitleDto $title The title to append.
     * @return self Returns the current instance for method chaining.
     */
    public function addTitle($title)
    {
        if (!isset($this->title)) {
            $this->title = array();
        }
        
        $this->title[] = $title; // Append the column title
        
        return $this; // Returns the current instance for method chaining.
    }
    
    /**
     * Append a data map to the table.
     *
     * @param DataMap $dataMap The data map to append.
     * @return self Returns the current instance for method chaining.
     */
    public function addDataMap($dataMap)
    {
        if (!isset($this->dataMap)) {
            $this->dataMap = array();
        }
        
        $this->dataMap[] = $dataMap; // Append the data map
        
        return $this; // Returns the current instance for method chaining.
    }
    
    /**
     * Append a row of data to the table.
     *
     * This method adds a new row to the internal row collection using the provided
     * MagicObject as data along with the associated MetadataDto.
     *
     * @param MagicObject $data The row data to append.
     * @param MetadataDto $metadata The metadata associated with the row data.
     * @return self Returns the current instance for method chaining.
     */
    public function addData($data, $metadata)
    {
        if (!isset($this->row)) {
            $this->row = array();
        }
        
        $this->row[] = new RowDto($data, $metadata); // Create and append new RowDto
        
        return $this; // Returns the current instance for method chaining.
    }

    /**
     * Get an array of column titles for the data table.
     *
     * @return ListDataTitleDto[] The column titles.
     */ 
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * Reset the column titles to an empty array.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function resetTitle()
    {
        $this->title = array(); // Resetting title array
        return $this; // Returns the current instance for method chaining.
    }

    /**
     * Get the data map for the table.
     *
     * @return DataMap[] The data map.
     */ 
    public function getDataMap()
    {
        return $this->dataMap;
    }
    
    /**
     * Reset the data map to an empty array.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function resetDataMap()
    {
        $this->dataMap = array(); // Resetting data map array
        return $this; // Returns the current instance for method chaining.
    }
    
    /**
     * Get an array of row for the data table.
     *
     * @return RowDto[] The row of data.
     */
    public function getRow()
    {
        return $this->row;
    }

    /**
     * Reset the row to an empty array.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function resetRow()
    {
        $this->row = array(); // Resetting row array
        return $this; // Returns the current instance for method chaining.
    }

    /**
     * Get current page
     *
     * @return PageDto
     */ 
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set current page
     *
     * @param PageDto $page Current page
     *
     * @return self Returns the current instance for method chaining.
     */ 
    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get an associative array mapping primary key names to their data types.
     *
     * @return string[]
     */ 
    public function getPrimaryKeyDataType()
    {
        return $this->primaryKeyDataType;
    }

    /**
     * Set an associative array mapping primary key names to their data types.
     *
     * @param string[] $primaryKeyDataType An associative array mapping primary key names to their data types.
     *
     * @return self Returns the current instance for method chaining.
     */ 
    public function setPrimaryKeyDataType($primaryKeyDataType)
    {
        $this->primaryKeyDataType = $primaryKeyDataType;

        return $this;
    }

    /**
     * Set an array of data maps for the data table.
     *
     * @param DataMap[]  $dataMap  An array of data maps for the data table.
     *
     * @return self Returns the current instance for method chaining.
     */ 
    public function setDataMap($dataMap)
    {
        $this->dataMap = $dataMap;

        return $this;
    }
    
    /**
     * Adds a ButtonFormData object to the internal collection of data controls.
     * This method stores the given data control for further use or processing.
     *
     * @param ButtonFormData $dataControl The ButtonFormData object to be added to the collection.
     * @return self Returns the current object instance for method chaining.
     */
    public function addDataControl($dataControl)
    {
        $this->dataControl[] = $dataControl;
        return $this;
    }
}
