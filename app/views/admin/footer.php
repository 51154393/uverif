<?php if(!defined('U_ADMIN')){exit;}?>
						</div>
					</div>
				</div>
				<div class="col-md-12 text-center" id="div_appAlter" style="margin-top: calc(20vh);"  hidden>
					<img src="/assets/images/default/appAlter.svg" class="avatar-img" style="width: 200px;">
					<p class="mt-2 h6">当前操作应用已变更</p>
					<button type="button" class="btn btn-primary mr-2 my-2" onclick="location.reload()">刷新页面</button>
					<a type="button" class="btn btn-light" href="/admin/index">返回主页</a>
				</div>
				
				<div class="col-md-12 text-center" id="div_error" style="margin-top: calc(20vh);"  hidden>
					<img src="/assets/images/default/error.svg" class="avatar-img" style="width: 200px;">
					<p class="mt-2 h6">页面数据加载出错了，请打开浏览器调试模式（F12）查看错误原因</p>
					<button type="button" class="btn btn-primary mr-2 my-2" onclick="location.reload()">刷新页面</button>
					<a type="button" class="btn btn-light" href="/admin/index">返回主页</a>
				</div>
			</main>
			<div class="modal-backdrop fade show" id="loading">
				<div class="d-flex justify-content-center" style="margin-top: calc(49vh);">
					<div class="spinner-border text-white" role="status"></div>
				</div>
			</div>
			<div class="modal fade" id="adm_cap" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="modal_title">
								管理员登录信息修改
							</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<form>
								<div class="form-group">
									<label>账号</label>
									<input type="text" class="form-control" value="<?echo $this->admConf['ADM_USER'];?>" id="adm_cap_user">
								</div>
								<div class="form-group">
									<label>当前密码</label>
									<input type="text" class="form-control" placeholder="请输入当前密码" id="adm_cap_pwd">
								</div>
								<div class="form-group">
									<label>新密码</label>
									<input type="text" class="form-control" placeholder="请设置新密码" id="adm_cap_newpwd">
								</div>
								<div class="form-group mb-2">
									<button type="submit" class="btn btn-primary btn-block" id="adm_submit">确认修改</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			
			<div class="modal fade modal-right modal-slide" id="set" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        		<div class="modal-dialog modal-plus-min" role="document">
        			<div class="modal-content">
                        <div class="modal-header">
                        	<h5 class="modal-title" id="modal_title">
                        		系统设置
                        	</h5>
                        	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        		<span aria-hidden="true">&times;</span>
                        	</button>
                        </div>
        				<div class="modal-body bg-white">
                            <form>
                                <div class="form-group">
                            		<label>系统URL</label>
                            		<input type="text" class="form-control" value="<?=$this->appConf['WEB_URL']?>" id="set_web_url">
                            	</div>
                            	<div class="form-group">
                            		<label>页面数据条数</label>
                            		<input type="number" class="form-control" value="<?=$this->appConf['APP_PAGE_ENUMS']?>" id="set_app_page_enums">
                            	</div>
                            	<div class="form-group">
                            		<label>记录管理员日志</label>
                            		<select id="set_app_adm_log" class="form-control">
                            			<option value="on" <?echo $this->appConf['APP_ADM_LOG']=='on'?'selected':'';?>>开启</option>
                            			<option value="off" <?echo $this->appConf['APP_ADM_LOG']=='off'?'selected':'';?>>关闭</option>
                            		</select>
                            	</div>
                            	<div class="form-group">
                            		<label>记录用户日志</label>
                            		<select id="set_app_user_log" class="form-control">
                            			<option value="on" <?echo $this->appConf['APP_USER_LOG']=='on'?'selected':'';?>>开启</option>
                            			<option value="off" <?echo $this->appConf['APP_USER_LOG']=='off'?'selected':'';?>>关闭</option>
                            		</select>
                            	</div>
                            	<div class="form-group">
                            		<label>上传文件字节限制</label>
                            		<div class="input-group">
                            			<input type="number" class="form-control" value="<?=$this->appConf['USER_UPFILE_SIZE'];?>" id="set_user_upfile_size">
                            			<div class="input-group-append">
                            				<span class="input-group-text">M</span>
                            			</div>
                            		</div>
                            	</div>
                            	<div class="form-group">
                            		<label>API输出类型</label>
                            		<select id="set_api_out_type" class="form-control">
                            			<option value="json" <?echo $this->appConf['API_OUT_TYPE']=='json'?'selected':'';?>>JSON</option>
                            			<option value="xml" <?echo $this->appConf['API_OUT_TYPE']=='xml'?'selected':'';?>>XML</option>
                            		</select>
                            	</div>
								<div class="form-group">
                            		<label>API白名单<span class="fe fe-help-circle ml-1" data-toggle="tooltip" title="白名单内的api接口不进行加解密验证，每个接口用逗号（,）隔开"></span></label>
									<input type="text" class="form-control" value="<?=$this->appConf['API_WHITE'];?>" id="set_api_white" placeholder="白名单内的api接口不进行加解密验证，每个接口用逗号（,）隔开">
                            	</div>
								<div class="form-group">
                            		<label>API运算成本</label>
                            		<select id="set_api_run_cost" class="form-control">
                            			<option value="on" <?echo $this->appConf['API_RUN_COST']=='on'?'selected':'';?>>开启</option>
                            			<option value="off" <?echo $this->appConf['API_RUN_COST']=='off'?'selected':'';?>>关闭</option>
                            		</select>
                            	</div>
                            	<div class="form-group">
                            		<label>系统缓存开关<span class="fe fe-help-circle ml-1" data-toggle="tooltip" title="系统默认是开启总览数据缓存的，可以避免数据量较大时减轻服务器压力"></span></label>
                            		<select id="set_sys_cache" class="form-control">
                            			<option value="on" <?if(!U_CACHE){echo 'selected';}?>>开启</option>
                            			<option value="off" <?if(U_CACHE){echo 'selected';}?>>关闭</option>
                            		</select>
                            	</div>
								<div class="form-group">
                            		<label>系统报错开关</label>
                            		<select id="set_sys_error" class="form-control">
                            			<option value="on" <?if(U_ERROR){echo 'selected';}?>>开启</option>
                            			<option value="off" <?if(!U_ERROR){echo 'selected';}?>>关闭</option>
                            		</select>
                            	</div>
								<div class="form-group">
									<label>系统调试模式</label>
									<select id="set_sys_debug" class="form-control">
										<option value="on" <?if(U_DEBUG){echo 'selected';}?>>开启</option>
										<option value="off" <?if(!U_DEBUG){echo 'selected';}?>>关闭</option>
									</select>
								</div>
								<div class="form-group">
									<label>错误信息上传<span class="fe fe-help-circle ml-1" data-toggle="tooltip" title="开启此功能可后系统会将错误报告上传至U验证服务器，有利于我们发现BUG并进行修复"></span></label>
									<select id="set_error_uploading" class="form-control">
										<option value="on" <?if(U_ERROR_UPLOADING){echo 'selected';}?>>开启</option>
										<option value="off" <?if(!U_ERROR_UPLOADING){echo 'selected';}?>>关闭</option>
									</select>
								</div>
                            	<div class="form-group mb-2">
                            		<button type="submit" class="btn btn-primary btn-block" id="set_submit">确认编辑</button>
                            	</div>
                            </form>
        				</div>
        			</div>
        		</div>
			</div>	
		</div>
		<script>
			var initSubmit = {act:'get',pg:1},appid;
			function app_pg_verification(){var e="hidden"in document?"hidden":"webkitHidden"in document?"webkitHidden":"mozHidden"in document?"mozHidden":null,a=e.replace(/hidden/i,"visibilitychange");document.addEventListener(a,function(){document[e]||appid!=getCookie("appid")&&($("#div_appAlter").attr("hidden",!1),$("#div_container").attr("hidden",!0),$(".modal").modal("hide"))}),window.localStorage.setItem("fwbl_prePage","1"),window.localStorage.setItem("money_use","1")}function getSO(e){for(var a=document.getElementsByName("so"),t=0;t<a.length;t++)e[a[t].id]=a[t].value;return e}function doPage(e,a){let t=Object.assign({},initSubmit);t.act="get",t.pg=a,$("#page_"+e).attr("disabled",!0),$("#page_"+e).html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>'),init(t,{k:"pg",id:e})}function page_list(e,a,t){if($("#poge_list_ul").empty(),e.length>1){$page_pre='<li class="page-item"><a class="page-link" id="page_pre" href="javascript:doPage(\'pre\','+(a-1)+')">上一页</a></li>',$page_next='<li class="page-item"><a class="page-link" id="page_next" href="javascript:doPage(\'next\','+(a+1>t?a:a+1)+')">下一页</a></li>',$page_data="";for(var i=0;i<e.length;i++)e[i]==a?$page_data+='<li class="page-item active"><a class="page-link" id="page_'+e[i]+'" href="javascript:doPage(\''+e[i]+"',"+e[i]+')">'+e[i]+"</a></li>":$page_data+='<li class="page-item"><a class="page-link" id="page_'+e[i]+'" href="javascript:doPage(\''+e[i]+"',"+e[i]+')">'+e[i]+"</a></li>";$("#poge_list_ul").append($page_pre+$page_data+$page_next)}}function modal(e){$("#"+e).modal("show")}function loading(e){$("#loading").attr("hidden",!e)}function initSuccess(e,a={}){200==e.code?(add_list(e.data.list,!0),e.data.pageList&&page_list(e.data.pageList,e.data.currentPage,e.data.maxPage),loading(!1)):(cocoMessage.error(e.msg,2e3),loading(!1)),"so"==a.k&&($("#so_submit").html("搜索"),$("#so_submit").attr("disabled",!1))}function initError(e,a={}){if(cocoMessage.error(e,2e3),$("#div_error").attr("hidden",!1),$("#div_container").attr("hidden",!0),loading(!1),"pg"==a.k){var t="pre"==a.id?"&laquo;":"next"==a.id?"&raquo;":a.id;$("#page_"+a.id).html(t),$("#page_"+a.id).attr("disabled",!1)}"so"==a.k&&($("#so_submit").html("搜索"),$("#so_submit").attr("disabled",!1))}window.onload=function(){appid=getCookie("appid"),"function"==typeof init?init(initSubmit):loading(!1),app_pg_verification(),$('[data-toggle="tooltip"]').tooltip()},$("#adm_submit").click(function(){var e={};return e.user=$("#adm_cap_user").val(),e.pwd=$("#adm_cap_pwd").val(),e.newpwd=$("#adm_cap_newpwd").val(),$("#adm_submit").html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>修改中'),$("#adm_submit").attr("disabled",!0),$.ajax({cache:!1,type:"POST",url:"/admin/cap",data:e,dataType:"json",success:function(e){$("#adm_submit").html("确认修改"),$("#adm_submit").attr("disabled",!1),200==e.code?(cocoMessage.success(e.msg,2e3),location.reload()):cocoMessage.error(e.msg,2e3)},error:function(e,a,t){cocoMessage.error("操作失败，可能是系统报错了，建议打开F12查看错误信息",2e3),$("#adm_submit").html("确认修改"),$("#adm_submit").attr("disabled",!1)}}),!1}),$("#set_submit").click(function(){var e={};return e.web_url=$("#set_web_url").val(),e.app_page_enums=$("#set_app_page_enums").val(),e.app_adm_log=$("#set_app_adm_log").val(),e.app_user_log=$("#set_app_user_log").val(),e.api_white=$("#set_api_white").val(),e.user_upfile_size=$("#set_user_upfile_size").val(),e.api_out_type=$("#set_api_out_type").val(),e.api_run_cost=$("#set_api_run_cost").val(),e.sys_error=$("#set_sys_error").val(),e.sys_debug=$("#set_sys_debug").val(),e.sys_cache=$("#set_sys_cache").val(),e.error_uploading=$("#set_error_uploading").val(),$("#set_submit").html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>编辑中'),$("#set_submit").attr("disabled",!0),$.ajax({cache:!1,type:"POST",url:"/admin/set",data:e,dataType:"json",success:function(e){$("#set_submit").html("确认编辑"),$("#set_submit").attr("disabled",!1),200==e.code?cocoMessage.success(e.msg,2e3):cocoMessage.error(e.msg,2e3)},error:function(e,a,t){cocoMessage.error("操作失败，可能是系统报错了，建议打开F12查看错误信息",2e3),$("#set_submit").html("确认编辑"),$("#set_submit").attr("disabled",!1)}}),!1}),$("#so_submit").click(function(){let e=Object.assign({},initSubmit);e.act="get",e.pg=1,e=getSO(e),$("#so_submit").html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>搜索中'),$("#so_submit").attr("disabled",!0),init(e,{k:"so"})});
		</script>	
		<script src="/assets/js/coco-message.js"></script>
		<script src="/assets/js/app.js" type="text/javascript"></script>
		<script src="/assets/js/ue.min.js" type="text/javascript"></script>
	</body>
</html>