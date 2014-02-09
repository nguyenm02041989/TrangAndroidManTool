<?php 

/**
 * The interface IController tells what the controller must implement.
 * 
 * @author trang
 *
 */
interface IController
{
	// Add functions
	public function AddTask();
	public function AddSaveTask();
	public function AddSuccessTask();
	
	// Check form functions
	public function CheckForm();
	public function CheckFormAdd();
	public function CheckFormEdit();
	
	// Edit functions
	public function EditSaveTask();
	public function EditSuccessTask();
	
	// Handles errors
	public function ErrorTask();
	
	// List functions
	public function ListTask();
	
	// Map the request data to an object
	public function MapRequestDataToObject();
	
	// Search function
	public function SearchTask();
	
	// Validation functions of e.g. input field E-mail, name etc..
	public function ValidateTask();
	public function ValidateChangeTask();
	
	// Validation of form data
	public function ValidateFormTask();
	public function ValidateFormEditTask();
	
	// View data functions
	public function ViewTask();
	
}