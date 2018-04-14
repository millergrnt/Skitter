require 'elasticsearch/model'
require 'json'

class AddSkitReplyController < ApplicationController
def result
  @content = params[:content].inspect

  if @content.length > 140
    @response.save
  else
    @client = Elasticsearch::Client.new log:true
    @user_id = Integer(params[:user_id])
    @originalSkitID = Integer(params[:originalSkitID])

    #Get the newest ID from the elasticsearch DB
    @currentNewestID = @client.search index: 'skits', body:{ sort: [{ "skitID": {"order": "desc"} }], size: 1, query: {"match_all": {}}}
    @currentNewestID = @currentNewestID["hits"]["hits"][0]["_source"]["skitID"] += 1
    @contentLen = @content.length
    @content = @content[1...@contentLen - 1]

    #Create the new skit which is the reply
    @client.index index: 'skits', type: 'skit', body: {'skitID': @currentNewestID, 'ownerID': @user_id, 'content': @content, 'replyTo': @originalSkitID, 'replies': [-1]}, refresh: true

    #Update who it is a reply to
    @elasticResponse = @client.search index: 'skits', body:{ query: {match: {skitID: @originalSkitID}}}
    @replies = @elasticResponse["hits"]["hits"][0]["_source"]["replies"]
    @skitElasticID = @elasticResponse["hits"]["hits"][0]["_id"]
    if @replies[0] == -1
      @replies[0] = @currentNewestID
    else
      @replies.push(@currentNewestID)
    end

    @client.update index: 'skits', type: 'skit', id: @skitElasticID, body:{ doc: {"replies": @replies}}, refresh: true
    render plain: "Success"
  end
end
end