<!-- MAIN -->
<div id="Main">
	<div id="container">
		<!--ul id="tab">
			<li class="present" id="green"><a href="#page1"><img src="./images/touroku.gif" alt="touroku"></a></li>
			<li id="yellow"><a href="#page2"><img src="./images/henkou.gif" alt="henkou"></a></li>
			<li id="red"><a href="#page3"><img src="./images/sakujyo.gif" alt="sakujyo"></a></li>
		</ul-->
        
		<ul id="tab">
			<input type="button" id="tab_edit" name="touroku" onClick="location.href='./admin_user/';">
            <input type="button" id="tab_update" name="henkou" onClick="location.href='./admin_user/update/';">
			<input type="button" id="tab_delete" name="sakujyo" onClick="location.href='./admin_user/delete/';">
		</ul>
        
		<div id="page1">
            <table>
                <tr>
                    <td><?php echo $edit_table; ?></td>
                </tr>
            </table>
			
		</div>
	</form>
	</div>
</div>
