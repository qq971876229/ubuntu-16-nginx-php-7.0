#趣聊数据库表对应字段
##users 表(用户)
| 字段名 | 备注
| ----- | :-------:| 
| id | 主键
| auth_state | 认证状态0,开始,1待认证（申请成为主播）,2是通过 
| online_state | 在线状态<br/>  40/free（空闲<br/>   30/busy(忙碌)<br/>   20/disturb(请勿打扰)<br/>  10/offline(离线）
| reference | 推荐人id
| reference_time | 有效时间戳（超过这个时间，就不会有推荐奖励了）
| request_time | 请求时间戳（超过当前时间戳  300秒 就为离线，online_state 就不用了）



##money 表(充值，消费)
| 字段名 | 备注
| ----- | :-------:| 
| id | 主键
| uid | 用户id 
| value | 数值（消费为负数，充值为正数）
| remark | 备注
| type | 类型  1/recharge(充值)<br/>  10/debug(测试充值)<br/>  20/view_live(观众扣费)<br/>  21/live_live(主播收费)<br/>  30/gift(送礼物)<br/>   31/gift(收礼物)<br/> 40/reference(推荐)<br/>   50/mess(消息)<br/>  
| add_time | 添加时间
| tied | 关联单据号


##vod_session 表
state 0开始请求，1，开始视频，2结束视频, 3未应答
live_ttl  主播请求链接过期时间   用户请求时间+30秒 这个30秒是 vodSessionModel::$ttl 中设置的
view_ttl  用户请求链接过期时间   用户请求时间+30秒 这个30秒是 vodSessionModel::$ttl 中设置的




| sex | 1/Gender_Type_Male(男性)  2/Gender_Type_Female(女性，空默认为女性)


